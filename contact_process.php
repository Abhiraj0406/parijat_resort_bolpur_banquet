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
    
    // 2. Send SMTP Notification Email to Reservations Desk
    $adminNotifyEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'reservations@parijatresortbolpur.com';
    $body = "<h3>New Contact Enquiry Received</h3>
             <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
             <p><strong>Phone:</strong> " . htmlspecialchars($phone) . "</p>
             <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
             <p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
             <p><strong>Message:</strong><br/>" . nl2br(htmlspecialchars($message)) . "</p>";
             
    $success = send_smtp_email($adminNotifyEmail, "New Contact Enquiry: " . $subject, $body);
    
    // 3. User feedback
    echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 25px; border-radius: 8px; background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32;'>";
    echo "<h3 style='margin-top:0;'>Thank You for Contacting Us!</h3>";
    echo "<p>Your message has been received by our reservations desk. We will get back to you shortly.</p>";
    echo "<p><a href='index.php' style='color: #1b5e20; font-weight: bold;'>&larr; Return to Homepage</a></p>";
    echo "</div>";
}
?>
