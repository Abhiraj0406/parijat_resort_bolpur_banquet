<?php
// book_process.php – Process room booking enquiry, validate server-side, store in MySQL, and redirect to WhatsApp
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/email.php';

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
    header('Location: booking.php?error=' . urlencode('Invalid security session token. Please try again.'));
    exit;
}

// Helper to redirect with error
function redirect_error($msg) {
    $cat = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    $catParam = $cat > 0 ? '&category=' . $cat : '';
    if (isset($_POST['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'error', 'message' => $msg]);
        exit;
    }
    header('Location: booking.php?error=' . urlencode($msg) . $catParam);
    exit;
}

// 1. Retrieve & sanitize inputs
$name             = trim($_POST['name'] ?? '');
$phone            = trim($_POST['phone'] ?? '');
$whatsapp_number  = trim($_POST['whatsapp_number'] ?? '');
$email            = trim($_POST['email'] ?? '');
$check_in         = trim($_POST['check_in'] ?? '');
$check_out        = trim($_POST['check_out'] ?? '');
$category_id      = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
$ac_preference    = in_array($_POST['ac_preference'] ?? '', ['AC', 'Non-AC']) ? $_POST['ac_preference'] : 'AC';
$number_of_rooms  = isset($_POST['number_of_rooms']) ? max(1, (int)$_POST['number_of_rooms']) : 1;
$adults           = isset($_POST['adults']) ? max(1, (int)$_POST['adults']) : 2;
$kids             = isset($_POST['kids']) ? max(0, (int)$_POST['kids']) : 0;
$message          = trim($_POST['message'] ?? '');

// 2. Server-side validation
if (empty($name)) {
    redirect_error('Customer name is required.');
}

if (strlen($name) < 2 || strlen($name) > 100) {
    redirect_error('Customer name must be between 2 and 100 characters.');
}

if (empty($phone)) {
    redirect_error('Mobile phone number is required.');
}

// Validate phone digits
$cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
if (strlen(preg_replace('/[^0-9]/', '', $cleanPhone)) < 10) {
    redirect_error('Please provide a valid 10-digit mobile number.');
}

if (empty($whatsapp_number)) {
    $whatsapp_number = $phone; // Fallback to mobile if left blank
}
$cleanWhatsApp = preg_replace('/[^0-9+]/', '', $whatsapp_number);
if (strlen(preg_replace('/[^0-9]/', '', $cleanWhatsApp)) < 10) {
    redirect_error('Please provide a valid WhatsApp number.');
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_error('Please provide a valid email address.');
}

if (empty($check_in) || empty($check_out)) {
    redirect_error('Both check-in and check-out dates are required.');
}

$inTime = strtotime($check_in);
$outTime = strtotime($check_out);
$todayTime = strtotime(date('Y-m-d'));

if ($inTime === false || $outTime === false) {
    redirect_error('Invalid date format provided.');
}

if ($inTime < $todayTime) {
    redirect_error('Check-in date cannot be in the past.');
}

if ($outTime <= $inTime) {
    redirect_error('Check-out date must be after check-in date.');
}

if ($category_id <= 0) {
    redirect_error('Please select a valid room category.');
}

// Fetch Category Name
$cStmt = $pdo->prepare("SELECT name FROM room_categories WHERE id = :id");
$cStmt->execute([':id' => $category_id]);
$category_name = $cStmt->fetchColumn();

if (!$category_name) {
    redirect_error('The selected room category does not exist.');
}

try {
    // 3. Save Enquiry in MySQL (booking_inquiries)
    $sqlInq = "INSERT INTO booking_inquiries 
        (guest_name, phone, whatsapp_number, email, check_in, check_out, category_id, ac_preference, number_of_rooms, adults, kids, message, status) 
        VALUES (:guest_name, :phone, :wa, :email, :check_in, :check_out, :category_id, :ac_pref, :num_rooms, :adults, :kids, :message, 'new')";
    
    $stmtInq = $pdo->prepare($sqlInq);
    $stmtInq->execute([
        ':guest_name'  => $name,
        ':phone'       => $phone,
        ':wa'          => $whatsapp_number,
        ':email'       => $email,
        ':check_in'    => $check_in,
        ':check_out'   => $check_out,
        ':category_id' => $category_id,
        ':ac_pref'     => $ac_preference,
        ':num_rooms'   => $number_of_rooms,
        ':adults'      => $adults,
        ':kids'        => $kids,
        ':message'     => $message,
    ]);
    $inquiryId = $pdo->lastInsertId();

    // Also record in reservations table for reference
    $sqlRes = "INSERT INTO reservations (name, email, phone, check_in, check_out, room_type, message) 
               VALUES (:name, :email, :phone, :check_in, :check_out, :room_type, :message)";
    $stmtRes = $pdo->prepare($sqlRes);
    $roomTypeFull = $category_name . " ($ac_preference) - $number_of_rooms Room(s)";
    $stmtRes->execute([
        ':name'      => $name,
        ':email'     => $email ?: 'no-email@parijatresortbolpur.com',
        ':phone'     => $phone,
        ':check_in'  => $check_in,
        ':check_out' => $check_out,
        ':room_type' => $roomTypeFull,
        ':message'   => $message
    ]);
    $reservationId = $pdo->lastInsertId();

    $refId = 'RES-' . str_pad($reservationId ?: $inquiryId, 5, '0', STR_PAD_LEFT);
    $formattedCheckIn  = date('d M Y', $inTime);
    $formattedCheckOut = date('d M Y', $outTime);
    $displayMessage    = !empty($message) ? $message : 'None';

    // 4. Send Email notifications directly to resort admin email
    $resortEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : (getenv('ADMIN_EMAIL') ?: 'parijatresort21@gmail.com');
    $subject = "New Room Booking Enquiry #$refId - $name ($category_name)";

    // Fetch resort contact details for customer emails
    $settStmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ('phone_number', 'whatsapp_number')");
    $siteSettings = $settStmt->fetchAll(PDO::FETCH_KEY_PAIR);
    $resortPhone = !empty($siteSettings['phone_number']) ? $siteSettings['phone_number'] : '+91 98309 09875';
    $resortWhatsApp = !empty($siteSettings['whatsapp_number']) ? $siteSettings['whatsapp_number'] : '+91 98309 09875';
    $cleanResortPhone = preg_replace('/[^0-9+]/', '', $resortPhone);
    $cleanResortWa = preg_replace('/[^0-9]/', '', $resortWhatsApp);
    if (strlen($cleanResortWa) === 10) $cleanResortWa = '91' . $cleanResortWa;

    $cleanWaDigits = preg_replace('/[^0-9]/', '', $whatsapp_number);
    if (strlen($cleanWaDigits) === 10) $cleanWaDigits = '91' . $cleanWaDigits;

    // --- Admin Notification Email ---
    $adminHtmlEmail = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Room Booking Enquiry</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px; color: #333;">
    <div style="max-width: 600px; background: #ffffff; margin: 0 auto; border-radius: 12px; overflow: hidden; border: 1px solid #e0e0e0; box-shadow: 0 4px 15px rgba(0,0,0,0.06);">
        <div style="background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%); color: #ffffff; padding: 24px 20px; text-align: center;">
            <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: #ffffff; letter-spacing: 0.5px;">Parijat Resort &amp; Banquet</h2>
            <p style="margin: 6px 0 0; opacity: 0.9; font-size: 14px; color: #e8f5e9;">New Room Booking Enquiry</p>
            <div style="display: inline-block; background: #fab915; color: #111; padding: 5px 16px; border-radius: 20px; font-weight: bold; font-size: 13px; margin-top: 10px;">
                Reference: #' . $refId . '
            </div>
        </div>
        <div style="padding: 24px 20px;">
            <p style="font-size: 15px; margin-top: 0; color: #444;">A customer has submitted a new online room enquiry through the resort website:</p>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; width: 38%; background: #fafafa;">Guest Name</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #111;">' . htmlspecialchars($name) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Mobile Number</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;"><a href="tel:' . htmlspecialchars($phone) . '" style="color: #1b5e20; text-decoration: none;">' . htmlspecialchars($phone) . '</a></td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">WhatsApp Number</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;"><a href="https://wa.me/' . $cleanWaDigits . '" style="color: #25d366; text-decoration: none;">' . htmlspecialchars($whatsapp_number) . '</a></td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Email Address</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; color: #333;">' . ($email ? htmlspecialchars($email) : 'Not provided') . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Room Category</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #1b5e20;">' . htmlspecialchars($category_name) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Climate Preference</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;">' . htmlspecialchars($ac_preference) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Number of Rooms</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;">' . $number_of_rooms . ' Room(s)</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Check-In Date</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;">' . date('d M Y (l)', $inTime) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Check-Out Date</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;">' . date('d M Y (l)', $outTime) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Total Guests</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;">' . $adults . ' Adult(s)' . ($kids > 0 ? ', ' . $kids . ' Kid(s)' : '') . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Special Requests</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-style: italic; color: #555;">' . ($message ? nl2br(htmlspecialchars($message)) : 'None') . '</td>
                </tr>
            </table>
        </div>
        <div style="text-align: center; padding: 20px; background: #f9fbf9; border-top: 1px solid #e8f5e9;">
            <a href="https://wa.me/' . $cleanWaDigits . '" style="display: inline-block; background: #25d366; color: #ffffff !important; text-decoration: none; padding: 12px 22px; border-radius: 25px; font-weight: bold; font-size: 14px; margin: 4px;">&#128172; Chat on WhatsApp</a>
            <a href="tel:' . htmlspecialchars($phone) . '" style="display: inline-block; background: #1b5e20; color: #ffffff !important; text-decoration: none; padding: 12px 22px; border-radius: 25px; font-weight: bold; font-size: 14px; margin: 4px;">&#128222; Call Guest</a>
        </div>
        <div style="padding: 14px; text-align: center; font-size: 12px; color: #888; background: #f0f2f5;">
            Official Automated Booking Desk &bull; Parijat Resort &amp; Banquet &bull; Near Shantiniketan
        </div>
    </div>
</body>
</html>';

    // --- Guest Confirmation Receipt Email ---
    $guestHtmlEmail = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Enquiry Received</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px; color: #333;">
    <div style="max-width: 600px; background: #ffffff; margin: 0 auto; border-radius: 12px; overflow: hidden; border: 1px solid #e0e0e0; box-shadow: 0 4px 15px rgba(0,0,0,0.06);">
        <div style="background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%); color: #ffffff; padding: 24px 20px; text-align: center;">
            <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: #ffffff; letter-spacing: 0.5px;">Parijat Resort &amp; Banquet</h2>
            <p style="margin: 6px 0 0; opacity: 0.9; font-size: 14px; color: #e8f5e9;">Booking Enquiry Confirmation Receipt</p>
            <div style="display: inline-block; background: #fab915; color: #111; padding: 5px 16px; border-radius: 20px; font-weight: bold; font-size: 13px; margin-top: 10px;">
                Reference ID: #' . $refId . '
            </div>
        </div>
        <div style="padding: 24px 20px;">
            <h3 style="margin-top: 0; font-size: 17px; color: #1b5e20;">Dear ' . htmlspecialchars($name) . ',</h3>
            <p style="font-size: 14.5px; line-height: 1.55; color: #444; margin-bottom: 18px;">
                Thank you for choosing <strong>Parijat Resort &amp; Banquet</strong>. We have successfully received your room booking enquiry. Our reservations manager is reviewing your dates and will contact you shortly to confirm room availability and tariff.
            </p>

            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; border: 1px solid #eee; border-radius: 8px; overflow: hidden;">
                <tr style="background: #f7faf7;">
                    <td colspan="2" style="padding: 10px 14px; font-weight: bold; color: #1b5e20; font-size: 14px; border-bottom: 1px solid #eee;">
                        Enquiry Details Summary
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; width: 40%; background: #fafafa;">Room Category</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #1b5e20;">' . htmlspecialchars($category_name) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Climate Preference</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;">' . htmlspecialchars($ac_preference) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Number of Rooms</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;">' . $number_of_rooms . ' Room(s)</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Check-In Date</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;">' . date('d M Y (l)', $inTime) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Check-Out Date</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;">' . date('d M Y (l)', $outTime) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Total Guests</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold;">' . $adults . ' Adult(s)' . ($kids > 0 ? ', ' . $kids . ' Kid(s)' : '') . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Contact Phone</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee;">' . htmlspecialchars($phone) . '</td>
                </tr>
                ' . (!empty($message) ? '<tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666; background: #fafafa;">Special Requests</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #eee; font-style: italic; color: #555;">' . nl2br(htmlspecialchars($message)) . '</td>
                </tr>' : '') . '
            </table>

            <div style="background: #fff8e1; border-left: 4px solid #fab915; padding: 12px 14px; margin-top: 18px; border-radius: 4px; font-size: 13.5px; color: #6d4c41; line-height: 1.45;">
                <strong>Next Steps:</strong> Our reservation manager will review room availability for your dates and reach out to you directly to confirm your booking and tariff.
            </div>
        </div>

        <div style="text-align: center; padding: 22px 20px; background: #f9fbf9; border-top: 1px solid #e8f5e9;">
            <p style="margin: 0 0 12px; font-size: 14px; font-weight: bold; color: #333;">Need immediate assistance? Connect with our front desk:</p>
            <a href="tel:' . $cleanResortPhone . '" style="display: inline-block; background: #1b5e20; color: #ffffff !important; text-decoration: none; padding: 11px 22px; border-radius: 25px; font-weight: bold; font-size: 13.5px; margin: 4px;">&#128222; Call Desk (' . htmlspecialchars($resortPhone) . ')</a>
            <a href="https://wa.me/' . $cleanResortWa . '?text=' . rawurlencode('Hello Parijat Resort, I have submitted an online room enquiry #' . $refId . ' under name ' . $name . '.') . '" style="display: inline-block; background: #25d366; color: #ffffff !important; text-decoration: none; padding: 11px 22px; border-radius: 25px; font-weight: bold; font-size: 13.5px; margin: 4px;">&#128172; Chat on WhatsApp</a>
        </div>

        <div style="padding: 16px; text-align: center; font-size: 12px; color: #777; background: #f0f2f5; line-height: 1.5;">
            <strong>Parijat Resort &amp; Banquet</strong><br>
            Sriniketan Rd, Bolpur, West Bengal 731236 (Near Shantiniketan)<br>
            Contact: ' . htmlspecialchars($resortPhone) . ' &bull; Email: ' . htmlspecialchars($resortEmail) . '
        </div>
    </div>
</body>
</html>';

    // Send Admin notification
    send_smtp_email($resortEmail, $subject, $adminHtmlEmail);

    // Send personalized customer receipt
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $guestSubject = "Booking Enquiry Received (#$refId) - Parijat Resort & Banquet";
        send_smtp_email($email, $guestSubject, $guestHtmlEmail);
    }

    // 5. Generate Pre-written Email for User's Email Client (Auto-compose)
    $emailPlainBody = "Dear Parijat Resort Reservations Team,\n\n"
                    . "I would like to enquire about a room booking at Parijat Resort & Banquet with the following details:\n\n"
                    . "====================================\n"
                    . "PARIJAT RESORT & BANQUET - ROOM ENQUIRY\n"
                    . "====================================\n"
                    . "Reference ID: #" . $refId . "\n"
                    . "Guest Name: " . $name . "\n"
                    . "Contact Number: " . $phone . "\n"
                    . "WhatsApp Number: " . $whatsapp_number . "\n"
                    . ($email ? "Email: " . $email . "\n" : "")
                    . "Room Category: " . $category_name . "\n"
                    . "Climate Preference: " . $ac_preference . "\n"
                    . "Number of Rooms: " . $number_of_rooms . " Room(s)\n"
                    . "Check-In Date: " . $formattedCheckIn . "\n"
                    . "Check-Out Date: " . $formattedCheckOut . "\n"
                    . "Total Guests: " . $adults . " Adult(s)" . ($kids > 0 ? ", " . $kids . " Kid(s)" : "") . "\n"
                    . "Special Requests: " . $displayMessage . "\n"
                    . "====================================\n\n"
                    . "Please confirm room availability and tariff rates.\n\n"
                    . "Thank you,\n"
                    . $name;

    $emailSubject = "Room Booking Enquiry #" . $refId . " - " . $name . " (" . $category_name . ")";
    $mailtoUrl = "mailto:" . $resortEmail . "?subject=" . rawurlencode($emailSubject) . "&body=" . rawurlencode($emailPlainBody);
    $gmailUrl = "https://mail.google.com/mail/?view=cm&fs=1&to=" . rawurlencode($resortEmail) . "&su=" . rawurlencode($emailSubject) . "&body=" . rawurlencode($emailPlainBody);

    $confirmationUrl = 'confirmation.php?id=' . $reservationId . '&email_sent=1';

    if (isset($_POST['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status'         => 'success',
            'reservation_id' => $reservationId,
            'ref_id'         => $refId,
            'email_sent'     => true,
            'mailto_url'     => $mailtoUrl,
            'gmail_url'      => $gmailUrl,
            'redirect'       => $confirmationUrl
        ]);
        exit;
    }

    header('Location: ' . $confirmationUrl);
    exit;

} catch (PDOException $e) {
    error_log('Reservation database error: ' . $e->getMessage());
    redirect_error('A temporary database error occurred while saving your request. Please try again.');
}
