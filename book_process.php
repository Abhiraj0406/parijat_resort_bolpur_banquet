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
    header('Location: booking.php?error=' . urlencode($msg));
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

// Fetch Primary Resort WhatsApp Number (Default +91 98309 09875)
$settStmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'whatsapp_number'");
$settStmt->execute();
$resortWhatsAppRaw = $settStmt->fetchColumn();
if (empty($resortWhatsAppRaw)) {
    $resortWhatsAppRaw = '+91 98309 09875';
}
// Clean target whatsapp number to digits only (e.g. 919830909875)
$targetWhatsAppDigits = preg_replace('/[^0-9]/', '', $resortWhatsAppRaw);
if (strlen($targetWhatsAppDigits) === 10) {
    $targetWhatsAppDigits = '91' . $targetWhatsAppDigits;
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

    // 4. Send Email notifications in background (non-blocking)
    $subject = 'New Room Booking Enquiry from ' . $name;
    $body = "New Room Booking Enquiry (ID #$inquiryId)\n\n"
          . "Name: $name\n"
          . "Mobile: $phone\n"
          . "WhatsApp: $whatsapp_number\n"
          . "Email: " . ($email ?: 'N/A') . "\n"
          . "Check-in: " . date('d M Y', $inTime) . "\n"
          . "Check-out: " . date('d M Y', $outTime) . "\n"
          . "Room Category: $category_name\n"
          . "AC/Non-AC: $ac_preference\n"
          . "Rooms: $number_of_rooms\n"
          . "Adults: $adults\n"
          . "Kids: $kids\n"
          . "Message: " . ($message ?: 'None');
    send_smtp_email('reservations@parijatresortbolpur.com', $subject, nl2br($body));

    // 5. Generate Properly Formatted WhatsApp Message
    $formattedCheckIn  = date('d M Y', $inTime);
    $formattedCheckOut = date('d M Y', $outTime);
    $displayMessage    = !empty($message) ? $message : 'None';

    $waMessage = "*New Room Booking Enquiry*\n\n"
               . "*Name:* $name\n"
               . "*Mobile:* $phone\n"
               . "*WhatsApp:* $whatsapp_number\n"
               . "*Check-in:* $formattedCheckIn\n"
               . "*Check-out:* $formattedCheckOut\n"
               . "*Room Category:* $category_name\n"
               . "*AC/Non-AC:* $ac_preference\n"
               . "*Rooms:* $number_of_rooms\n"
               . "*Adults:* $adults\n"
               . "*Kids:* $kids\n"
               . "*Message:* $displayMessage";

    $encodedWaText = rawurlencode($waMessage);
    $waUrl = "https://api.whatsapp.com/send?phone=" . $targetWhatsAppDigits . "&text=" . $encodedWaText;

    // 6. Redirect customer to confirmation page with automatic WhatsApp redirect modal & link
    header('Location: confirmation.php?id=' . $reservationId . '&wa=1&url=' . urlencode($waUrl));
    exit;

} catch (PDOException $e) {
    error_log('Reservation database error: ' . $e->getMessage());
    redirect_error('A temporary database error occurred while saving your request. Please try again.');
}
