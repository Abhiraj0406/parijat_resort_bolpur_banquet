<?php
$page_title = "Enquiry Submitted - Parijat Resort & Banquet";
$meta_description = "Your room booking enquiry has been recorded for Parijat Resort & Banquet.";
$og_image = "image/confirmation_og.jpg";
include_once 'includes/header.php';
require_once __DIR__.'/includes/config.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo '<div class="container my-5"><div class="alert alert-danger" role="alert">Invalid reservation inquiry ID.</div></div>';
    include_once 'includes/footer.php';
    exit;
}

$reservationId = (int)$_GET['id'];

$stmt = $pdo->prepare('SELECT name, email, phone, check_in, check_out, room_type, message, created_at FROM reservations WHERE id = :id');
$stmt->execute([':id' => $reservationId]);
$reservation = $stmt->fetch();

if (!$reservation) {
    // Fallback: check booking_inquiries table in case the ID was from booking_inquiries
    $stmtInq = $pdo->prepare("SELECT guest_name AS name, email, phone, check_in, check_out, COALESCE(NULLIF(ac_preference, ''), 'Quick Booking') AS room_type, message, created_at FROM booking_inquiries WHERE id = :id");
    $stmtInq->execute([':id' => $reservationId]);
    $reservation = $stmtInq->fetch();
}

if (!$reservation) {
    echo '<div class="container my-5"><div class="alert alert-warning" role="alert">Reservation enquiry not found.</div></div>';
    include_once 'includes/footer.php';
    exit;
}

$primaryPhone = $site_settings['phone_number'] ?? ($site_settings['whatsapp_number'] ?? '+91 98309 09875');
$refId = 'RES-' . str_pad($reservationId, 5, '0', STR_PAD_LEFT);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card confirmation-card shadow-lg border-0 rounded-lg overflow-hidden" style="background: rgba(18, 26, 20, 0.85) !important; backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.2) !important; border-radius: 16px; color: #ffffff;">
                <div class="card-header border-0 text-center pt-4 pb-3" style="background: linear-gradient(135deg, #102613 0%, #1b431e 100%); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                    <div class="mb-3">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 68px; height: 68px; background: rgba(46, 125, 50, 0.35); border: 2px solid #2e7d32; border-radius: 50%;">
                            <i class="fa fa-check text-success" style="font-size: 32px;"></i>
                        </span>
                    </div>
                    <h3 class="text-white font-weight-bold mb-1">Enquiry Request Submitted</h3>
                    <div class="mt-2">
                        <span class="badge badge-warning text-dark px-3 py-1 font-weight-bold" style="font-size: 13px; letter-spacing: 0.5px;">
                            Reference ID: #<?php echo $refId; ?>
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="alert border-0 rounded-lg p-3 mb-4 d-flex align-items-center" style="background: rgba(46, 125, 50, 0.2); border-left: 4px solid #4caf50 !important;">
                        <i class="fa fa-info-circle text-success mr-3 flex-shrink-0" style="font-size: 22px;"></i>
                        <div class="text-white-50 small mb-0" style="line-height: 1.45;">
                            Your reservation enquiry has been saved and sent to our reservations desk. Our team will verify room availability and contact you shortly.
                        </div>
                    </div>

                    <h5 class="font-weight-bold text-white border-bottom pb-2 mb-3" style="border-color: rgba(255,255,255,0.15) !important;">
                        <i class="fa fa-calendar-check-o text-warning mr-2"></i> Enquiry Summary
                    </h5>

                    <div class="rounded-lg p-3 mb-4" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div class="row m-0">
                            <div class="col-sm-6 p-2 d-flex justify-content-between" style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                                <span class="text-white-50 small">Guest Name:</span>
                                <strong class="text-white"><?php echo htmlspecialchars($reservation['name']); ?></strong>
                            </div>
                            <div class="col-sm-6 p-2 d-flex justify-content-between" style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                                <span class="text-white-50 small">Contact Phone:</span>
                                <strong class="text-white"><?php echo htmlspecialchars($reservation['phone']); ?></strong>
                            </div>
                            <?php if (!empty($reservation['email']) && $reservation['email'] !== 'no-email@parijatresortbolpur.com'): ?>
                            <div class="col-sm-6 p-2 d-flex justify-content-between" style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                                <span class="text-white-50 small">Email Address:</span>
                                <strong class="text-white"><?php echo htmlspecialchars($reservation['email']); ?></strong>
                            </div>
                            <?php endif; ?>
                            <div class="col-sm-6 p-2 d-flex justify-content-between" style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                                <span class="text-white-50 small">Room &amp; Category:</span>
                                <strong class="text-warning"><?php echo htmlspecialchars($reservation['room_type']); ?></strong>
                            </div>
                            <div class="col-12 p-2 d-flex justify-content-between" style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                                <span class="text-white-50 small">Stay Duration:</span>
                                <strong class="text-white"><?php echo date('d M Y', strtotime($reservation['check_in'])); ?> &rarr; <?php echo date('d M Y', strtotime($reservation['check_out'])); ?></strong>
                            </div>
                            <?php if (!empty($reservation['message'])): ?>
                            <div class="col-12 p-2">
                                <span class="text-white-50 small d-block">Special Requests:</span>
                                <span class="text-white font-italic">"<?php echo nl2br(htmlspecialchars($reservation['message'])); ?>"</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Navigation Actions -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center pt-3" style="border-top: 1px solid rgba(255,255,255,0.15); gap: 10px;">
                        <a href="./" class="btn btn-success font-weight-bold px-4 py-2">
                            <i class="fa fa-home mr-1"></i> Back to Home
                        </a>
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $primaryPhone); ?>" class="btn btn-outline-light font-weight-bold px-3 py-2">
                            <i class="fa fa-phone mr-1"></i> Call Front Desk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
