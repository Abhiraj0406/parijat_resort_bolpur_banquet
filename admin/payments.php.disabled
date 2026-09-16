<?php
// admin/payments.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';

// Handle Form Submission for New Payment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = (int)$_POST['booking_id'];
    $amount = (float)$_POST['amount'];
    $payment_method = trim($_POST['payment_method']);
    $transaction_ref = trim($_POST['transaction_ref'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($booking_id > 0 && $amount > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO payments (booking_id, amount, payment_method, transaction_ref, notes) VALUES (:bid, :amt, :pm, :tr, :nt)");
            $stmt->execute([
                ':bid' => $booking_id,
                ':amt' => $amount,
                ':pm' => $payment_method,
                ':tr' => $transaction_ref,
                ':nt' => $notes
            ]);
            $successMsg = "Payment recorded successfully.";
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    } else {
        $errorMsg = "Invalid booking ID or amount.";
    }
}

// Fetch all payments with booking details
$sql = "
    SELECT p.*, b.guest_name, b.check_in, b.check_out
    FROM payments p
    LEFT JOIN booking_inquiries b ON p.booking_id = b.id
    ORDER BY p.created_at DESC
";
$payments = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Calculate Totals
$totalRevenue = 0;
foreach ($payments as $p) {
    $totalRevenue += $p['amount'];
}

// Fetch confirmed/contacted bookings for the dropdown
$bookingsStmt = $pdo->query("SELECT id, guest_name, check_in, status FROM booking_inquiries WHERE status != 'cancelled' ORDER BY created_at DESC");
$activeBookings = $bookingsStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Payments Ledger</h2>
        <p class="text-muted mb-0">Record and track manual payments</p>
    </div>
    <button class="btn btn-primary px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
        <i class="bi bi-plus-lg me-1"></i> Record Payment
    </button>
</div>

<?php if ($errorMsg): ?>
    <div class="alert alert-danger shadow-sm border-0"><i class="bi bi-exclamation-circle me-2"></i><?php echo htmlspecialchars($errorMsg); ?></div>
<?php endif; ?>
<?php if ($successMsg): ?>
    <div class="alert alert-success shadow-sm border-0"><i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($successMsg); ?></div>
<?php endif; ?>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 pr-stats-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-muted mb-0">Total Collected</h6>
                    <div class="pr-icon-circle bg-success bg-opacity-10 text-success">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <h3 class="mb-0 fw-bold">₹<?php echo number_format($totalRevenue, 2); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 pr-stats-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-muted mb-0">Total Transactions</h6>
                    <div class="pr-icon-circle bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <h3 class="mb-0 fw-bold"><?php echo count($payments); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-list-columns-reverse me-2 text-primary"></i>Transaction History</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Date</th>
                        <th>Guest / Booking</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th class="text-end pe-3">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($payments) > 0): ?>
                        <?php foreach ($payments as $p): ?>
                        <tr>
                            <td class="ps-3">
                                <div class="fw-semibold"><?php echo date('d M Y', strtotime($p['created_at'])); ?></div>
                                <div class="text-muted small"><?php echo date('h:i A', strtotime($p['created_at'])); ?></div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($p['guest_name'] ?? 'Unknown'); ?></div>
                                <div class="text-muted" style="font-size: 0.8rem;">
                                    <?php if ($p['check_in']): ?>
                                        Booking: <?php echo date('d M Y', strtotime($p['check_in'])); ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php 
                                    $method = $p['payment_method'];
                                    $icon = 'cash';
                                    $color = 'success';
                                    if ($method == 'UPI') { $icon = 'qr-code-scan'; $color = 'primary'; }
                                    if ($method == 'Card') { $icon = 'credit-card'; $color = 'info text-dark'; }
                                    if ($method == 'Bank Transfer') { $icon = 'bank'; $color = 'secondary'; }
                                ?>
                                <span class="badge bg-<?php echo $color; ?> bg-opacity-10 text-<?php echo $color === 'info text-dark' ? 'dark' : $color; ?> rounded-pill px-3 py-2">
                                    <i class="bi bi-<?php echo $icon; ?> me-1"></i> <?php echo htmlspecialchars($method); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($p['transaction_ref'])): ?>
                                    <span class="text-muted" style="font-family: monospace; font-size: 0.85rem;"><?php echo htmlspecialchars($p['transaction_ref']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-3">
                                <div class="fw-bold text-success fs-5">₹<?php echo number_format($p['amount'], 2); ?></div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt" style="font-size: 2rem;"></i><br>
                                No payments recorded yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="addPaymentModalLabel">Record New Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="payments.php" method="POST">
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Booking <span class="text-danger">*</span></label>
                        <select class="form-select" name="booking_id" required>
                            <option value="">-- Choose a Guest Booking --</option>
                            <?php foreach ($activeBookings as $bk): ?>
                                <option value="<?php echo $bk['id']; ?>">
                                    <?php echo htmlspecialchars($bk['guest_name']); ?> (Check-in: <?php echo date('d M Y', strtotime($bk['check_in'])); ?>) - [<?php echo strtoupper($bk['status']); ?>]
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="amount" required placeholder="0.00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-select" name="payment_method" required>
                                <option value="Cash">Cash</option>
                                <option value="UPI">UPI / GPay / PhonePe</option>
                                <option value="Card">Credit / Debit Card</option>
                                <option value="Bank Transfer">Bank Transfer (NEFT/IMPS)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Transaction Reference ID</label>
                        <input type="text" class="form-control" name="transaction_ref" placeholder="e.g. UTR Number or Receipt No">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Optional details..."></textarea>
                    </div>
                    
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-semibold"><i class="bi bi-save me-1"></i> Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
