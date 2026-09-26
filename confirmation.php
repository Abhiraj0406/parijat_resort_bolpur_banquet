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
                    <div class="p-3 bg-light border rounded text-center mb-4">
                        <p class="font-weight-bold text-dark mb-2">Continue to WhatsApp to chat directly with our front desk:</p>
                        <a href="<?php echo htmlspecialchars($waUrl); ?>" target="_blank" id="waOpenBtn" class="btn btn-success btn-lg px-4 py-3 font-weight-bold shadow rounded-pill">
                            <i class="fa fa-whatsapp mr-2 fa-lg"></i> Open WhatsApp with Enquiry Details
                        </a>
                        <small class="d-block text-muted mt-2">If WhatsApp didn't open automatically, click the button above.</small>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Open WhatsApp in a new tab/window automatically after brief delay
    setTimeout(function() {
        var waLink = "<?php echo addslashes($waUrl); ?>";
        if (waLink) {
            window.open(waLink, '_blank');
        }
    }, 800);
});
</script>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
