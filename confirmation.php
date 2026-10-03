<?php
$page_title = "Enquiry Submitted - Parijat Resort Bolpur";
$meta_description = "Your room booking enquiry has been recorded for Parijat Resort Bolpur.";
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

$waUrl = isset($_GET['url']) ? $_GET['url'] : '';
$openWa = isset($_GET['wa']) && $_GET['wa'] == 1 && !empty($waUrl);
$primaryWhatsApp = $site_settings['whatsapp_number'] ?? '+91 98309 09875';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden text-center">
                <div class="card-header bg-success text-white py-4">
                    <div class="mb-2">
                        <i class="fa fa-check-circle-o fa-4x text-white"></i>
                    </div>
                    <h3 class="text-white font-weight-bold mb-1">Enquiry Request Submitted!</h3>
                    <p class="mb-0 text-white-50">Reference ID: #RES-<?php echo str_pad($reservationId, 5, '0', STR_PAD_LEFT); ?></p>
                </div>

                <div class="card-body p-4 p-md-5 text-left">
                    <div class="alert alert-warning border-warning d-flex align-items-center mb-4">
                        <i class="fa fa-info-circle fa-2x text-warning mr-3"></i>
                        <div>
                            <strong class="text-dark d-block font-weight-bold">Important Notice</strong>
                            <small class="text-dark">
                                Please note: This is an <strong>enquiry and availability request</strong>. Submitting this form does not automatically confirm your reservation. Our reservation team will verify room availability and confirm your booking tariff with you.
                            </small>
                        </div>
                    </div>

                    <h5 class="font-weight-bold text-dark border-bottom pb-2 mb-3">Enquiry Summary</h5>
                    <ul class="list-group list-group-flush mb-4 border rounded">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Guest Name:</span>
                            <strong><?php echo htmlspecialchars($reservation['name']); ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Contact Phone:</span>
                            <strong><?php echo htmlspecialchars($reservation['phone']); ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Room &amp; Selection:</span>
                            <strong class="text-primary"><?php echo htmlspecialchars($reservation['room_type']); ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Check‑In &rarr; Check‑Out:</span>
                            <strong><?php echo date('d M Y', strtotime($reservation['check_in'])); ?> &rarr; <?php echo date('d M Y', strtotime($reservation['check_out'])); ?></strong>
                        </li>
                        <?php if (!empty($reservation['message'])): ?>
                        <li class="list-group-item">
                            <span class="text-muted d-block">Special Requests:</span>
                            <span class="font-italic text-dark">"<?php echo nl2br(htmlspecialchars($reservation['message'])); ?>"</span>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <?php if (!empty($waUrl)): ?>
                    <div class="p-4 bg-light border rounded text-center mb-4 shadow-sm" style="border-radius: 12px;">
                        <h5 class="font-weight-bold text-dark mb-1">Send Booking Request to WhatsApp</h5>
                        <p class="text-muted small mb-3">Connecting with our front desk directly sends all your booking details to WhatsApp:</p>
                        <a href="<?php echo htmlspecialchars($waUrl); ?>" target="_blank" id="waOpenBtn" class="btn btn-success btn-lg px-4 py-3 font-weight-bold shadow rounded-pill d-inline-flex align-items-center">
                            <i class="fa fa-whatsapp mr-2 fa-2x"></i> <span>Open WhatsApp &amp; Send Details</span>
                        </a>
                        <small class="d-block text-muted mt-2" id="waStatusText">If WhatsApp didn't open automatically, click the green button above.</small>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <a href="./" class="btn btn-outline-secondary">
                            <i class="fa fa-home mr-1"></i> Back to Home
                        </a>
                        <button class="btn btn-outline-dark" onclick="window.print();">
                            <i class="fa fa-print mr-1"></i> Print Summary
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($openWa): ?>
<style>
@keyframes pulseGlow {
    0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); }
    70% { box-shadow: 0 0 0 14px rgba(37, 211, 102, 0); }
    100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
}
.pulse-btn {
    animation: pulseGlow 1.5s infinite;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var waLink = "<?php echo addslashes($waUrl); ?>";
    if (waLink) {
        var win = window.open(waLink, '_blank');
        if (!win || win.closed || typeof win.closed === 'undefined') {
            var statusEl = document.getElementById('waStatusText');
            if (statusEl) {
                statusEl.innerHTML = '<span class="text-success font-weight-bold"><i class="fa fa-info-circle"></i> Click the button above to launch WhatsApp.</span>';
            }
            var btn = document.getElementById('waOpenBtn');
            if (btn) {
                btn.classList.add('pulse-btn');
            }
        }
    }
});
</script>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
