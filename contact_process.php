<?php
// contact_process.php – Process Quick Booking and General Contact Form
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/email.php';

// Check form type
$type = $_POST['type'] ?? 'contact';

if ($type === 'quick_booking') {
    // Process Quick Booking from Homepage
    $name      = trim($_POST['name'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $check_in  = trim($_POST['check_in'] ?? '');
    $check_out = trim($_POST['check_out'] ?? '');
    
    if (empty($name) || empty($phone) || empty($check_in) || empty($check_out)) {
        die('Please fill all required fields.');
    }
    
    // Validate phone digits
    $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
    if (strlen(preg_replace('/[^0-9]/', '', $cleanPhone)) < 10) {
        die('Please provide a valid 10-digit mobile number.');
    }
    
    // Convert dates to Y-m-d for MySQL
    $inTime = strtotime(str_replace('/', '-', $check_in));
    $outTime = strtotime(str_replace('/', '-', $check_out));
    if (!$inTime || !$outTime) {
        die('Invalid date format.');
    }
    $check_in_db = date('Y-m-d', $inTime);
    $check_out_db = date('Y-m-d', $outTime);
    
    try {
        // Fetch a default category_id for Quick Booking
        $catStmt = $pdo->query("SELECT id FROM room_categories LIMIT 1");
        $defaultCategory = $catStmt->fetchColumn() ?: 1;

        // Insert into booking_inquiries table (Admin panel reads this)
        $sqlInq = "INSERT INTO booking_inquiries 
            (guest_name, email, phone, whatsapp_number, check_in, check_out, category_id, ac_preference, number_of_rooms, adults, kids, message, status) 
            VALUES (:guest_name, :email, :phone, :wa, :check_in, :check_out, :category_id, 'AC', 1, 2, 0, :message, 'new')";
        
        $stmtInq = $pdo->prepare($sqlInq);
        $stmtInq->execute([
            ':guest_name'  => $name,
            ':email'       => 'quick-booking@parijatresortbolpur.com',
            ':phone'       => $phone,
            ':wa'          => $phone,
            ':check_in'    => $check_in_db,
            ':check_out'   => $check_out_db,
            ':category_id' => $defaultCategory,
            ':message'     => 'Submitted via Quick Booking Form on Homepage.'
        ]);
        
        $reservationId = $pdo->lastInsertId();
        
        // Fetch Primary Resort WhatsApp Number
        $settStmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'whatsapp_number'");
        $settStmt->execute();
        $resortWhatsAppRaw = $settStmt->fetchColumn();
        if (empty($resortWhatsAppRaw)) {
            $resortWhatsAppRaw = '+91 98309 09875';
        }
        $targetWhatsAppDigits = preg_replace('/[^0-9]/', '', $resortWhatsAppRaw);
        if (strlen($targetWhatsAppDigits) === 10) {
            $targetWhatsAppDigits = '91' . $targetWhatsAppDigits;
        }
        
        // Generate WhatsApp Message
        $waMessage = "*New Quick Booking Enquiry*\n\n"
                   . "*Name:* $name\n"
                   . "*Mobile:* $phone\n"
                   . "*Check-in:* $check_in\n"
                   . "*Check-out:* $check_out\n"
                   . "*Source:* Homepage Quick Booking";
                   
        $encodedWaText = rawurlencode($waMessage);
        $waUrl = "https://api.whatsapp.com/send?phone=" . $targetWhatsAppDigits . "&text=" . $encodedWaText;
        
        // Redirect to confirmation
        header('Location: confirmation.php?id=' . $reservationId . '&wa=1&url=' . urlencode($waUrl));
        exit;
        
    } catch (Exception $e) {
        error_log('Quick booking error: ' . $e->getMessage());
        die('A database error occurred. Please try again.');
    }
    
} else {
    // Process General Contact Form
    $name    = trim($_POST['name'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? 'Website Contact Form');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || (empty($email) && empty($phone)) || empty($message)) {
        die('Name, contact number/email, and message are required.');
    }

    // 1. Record inquiry into contact_inquiries table for Admin Panel
    try {
        $stmtInq = $pdo->prepare("INSERT INTO contact_inquiries (name, email, phone, subject, message, status) VALUES (:name, :email, :phone, :subject, :message, 'new')");
        $stmtInq->execute([
            ':name'    => $name,
            ':email'   => $email ?: 'no-email@parijatresortbolpur.com',
            ':phone'   => $phone,
            ':subject' => $subject,
            ':message' => $message
        ]);
    } catch (PDOException $e) {
        error_log('Failed to record contact inquiry: ' . $e->getMessage());
    }
    
    // 2. Fetch Resort WhatsApp Number from site_settings
    $settStmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'whatsapp_number'");
    $settStmt->execute();
    $resortWhatsAppRaw = $settStmt->fetchColumn();
    if (empty($resortWhatsAppRaw)) {
        $resortWhatsAppRaw = '+91 98309 09875';
    }
    $targetWhatsAppDigits = preg_replace('/[^0-9]/', '', $resortWhatsAppRaw);
    if (strlen($targetWhatsAppDigits) === 10) {
        $targetWhatsAppDigits = '91' . $targetWhatsAppDigits;
    }

    // 3. Generate Formatted WhatsApp Message
    $waMessage = "🏨 *New Message / Enquiry from Parijat Resort Website*\n\n"
               . "👤 *Name:* $name\n"
               . "📞 *Phone:* $phone\n"
               . (!empty($email) ? "✉️ *Email:* $email\n" : "")
               . (!empty($subject) ? "📌 *Subject:* $subject\n" : "")
               . "💬 *Message:* $message\n\n"
               . "🌐 _Sent from Parijat Resort Contact Page_";
               
    $encodedWaText = rawurlencode($waMessage);
    $waUrl = "https://api.whatsapp.com/send?phone=" . $targetWhatsAppDigits . "&text=" . $encodedWaText;

    // 4. Send SMTP Notification Email to Reservations Desk (if configured)
    $adminNotifyEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'reservations@parijatresortbolpur.com';
    $body = "<h3>New Contact Enquiry Received</h3>
             <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
             <p><strong>Phone:</strong> " . htmlspecialchars($phone) . "</p>
             <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
             <p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
             <p><strong>Message:</strong><br/>" . nl2br(htmlspecialchars($message)) . "</p>";
             
    @send_smtp_email($adminNotifyEmail, "New Contact Enquiry: " . $subject, $body);

    // 5. Directly redirect to WhatsApp with friendly handover page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Redirecting to WhatsApp - Parijat Resort</title>
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <style>
            body {
                background: #0f172a;
                color: #ffffff;
                font-family: 'Poppins', sans-serif;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .wa-card {
                background: rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(16px);
                border-radius: 20px;
                padding: 40px 30px;
                max-width: 500px;
                width: 100%;
                text-align: center;
                box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            }
            .btn-wa {
                background: #25d366;
                color: #ffffff;
                font-weight: 700;
                font-size: 16px;
                border-radius: 50px;
                padding: 14px 28px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            }
            .btn-wa:hover {
                background: #1ebc59;
                color: #fff;
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <div class="wa-card">
            <div style="width: 80px; height: 80px; background: rgba(37, 211, 102, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                <i class="fa fa-whatsapp text-success" style="font-size: 45px;"></i>
            </div>
            <h3 style="font-weight: 700; margin-bottom: 10px; color: #fff;">Message Recorded!</h3>
            <p style="color: #cbd5e1; font-size: 14px; margin-bottom: 25px;">
                Connecting you to Parijat Resort's WhatsApp reservation desk to send your message instantly.
            </p>
            <a href="<?php echo htmlspecialchars($waUrl); ?>" class="btn-wa" id="waRedirectBtn">
                <i class="fa fa-whatsapp mr-2" style="font-size: 20px;"></i> Open WhatsApp Now
            </a>
            <div style="margin-top: 25px;">
                <a href="contact.php" style="color: #94a3b8; font-size: 13px; text-decoration: underline;">&larr; Back to Contact Page</a>
            </div>
        </div>

        <script>
            // Automatically open WhatsApp in 1 second
            setTimeout(function() {
                window.location.href = <?php echo json_encode($waUrl); ?>;
            }, 800);
        </script>
    </body>
    </html>
    <?php
    exit;
}
?>
