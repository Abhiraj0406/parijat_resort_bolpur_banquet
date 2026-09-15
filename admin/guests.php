<?php
// admin/guests.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

// Handle Search
$search = trim($_GET['search'] ?? '');
$params = [];

// Base Query: Group by email and phone to find unique guests
$sql = "
    SELECT 
        MAX(id) as latest_id,
        MAX(guest_name) as guest_name,
        email,
        phone,
        MAX(whatsapp_number) as whatsapp_number,
        COUNT(id) as total_bookings,
        MAX(check_in) as last_visit,
        MAX(created_at) as last_inquiry
    FROM booking_inquiries
    WHERE status != 'cancelled' 
";

if (!empty($search)) {
    $sql .= " AND (guest_name LIKE :search OR email LIKE :search OR phone LIKE :search) ";
    $params[':search'] = "%{$search}%";
}

$sql .= " GROUP BY email, phone ORDER BY last_visit DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $guests = [];
    $errorMsg = "Database error: " . $e->getMessage();
}

// Fetch all non-cancelled bookings to populate the history modals
try {
    $bStmt = $pdo->query("
        SELECT b.*, c.name as category_name 
        FROM booking_inquiries b 
        LEFT JOIN room_categories c ON b.category_id = c.id 
        WHERE b.status != 'cancelled'
        ORDER BY b.check_in DESC
    ");
    $allBookings = $bStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Group them by unique guest key
    $guestBookings = [];
    foreach ($allBookings as $b) {
        $key = md5(($b['email'] ?? '') . '_' . ($b['phone'] ?? ''));
        if (!isset($guestBookings[$key])) {
            $guestBookings[$key] = [];
        }
        $guestBookings[$key][] = $b;
    }
} catch (Exception $e) {
    $guestBookings = [];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Guest Directory</h2>
        <p class="text-muted mb-0">Total Unique Guests: <strong class="text-primary"><?php echo count($guests); ?></strong></p>
    </div>
</div>

<!-- Filters / Search -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="guests.php" class="row gx-2 gy-2 align-items-center">
            <div class="col-md-5">
                <input type="text" class="form-control" name="search" placeholder="Search by name, email, or phone..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i> Search</button>
                <a href="guests.php" class="btn btn-light"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Guests Table -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Guest Name</th>
                        <th>Contact Details</th>
                        <th class="text-center">Total Bookings</th>
                        <th>Last Visit (Check-in)</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($guests) > 0): ?>
                        <?php foreach ($guests as $guest): 
                            $guestKey = md5(($guest['email'] ?? '') . '_' . ($guest['phone'] ?? ''));
                            $history = $guestBookings[$guestKey] ?? [];
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                        <?php echo strtoupper(substr($guest['guest_name'], 0, 1)); ?>
                                    </div>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($guest['guest_name']); ?></div>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($guest['phone'])): ?>
                                    <div class="small mb-1"><i class="bi bi-telephone text-muted me-1"></i> <?php echo htmlspecialchars($guest['phone']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($guest['email'])): ?>
                                    <div class="small text-muted"><i class="bi bi-envelope me-1"></i> <?php echo htmlspecialchars($guest['email']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark rounded-pill px-3"><?php echo $guest['total_bookings']; ?> Stays</span>
                            </td>
                            <td>
                                <?php 
                                    if ($guest['last_visit']) {
                                        echo date('d M Y', strtotime($guest['last_visit'])); 
                                    } else {
                                        echo '<span class="text-muted">N/A</span>';
                                    }
                                ?>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#historyModal<?php echo $guestKey; ?>">
                                    <i class="bi bi-clock-history me-1"></i> View History
                                </button>
                            </td>
                        </tr>


                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-people" style="font-size: 2rem;"></i><br>
                                No guests found matching your criteria.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- History Modals -->
<?php foreach ($guests as $guest): 
    $guestKey = md5(($guest['email'] ?? '') . '_' . ($guest['phone'] ?? ''));
    $history = $guestBookings[$guestKey] ?? [];
?>
<div class="modal fade" id="historyModal<?php echo $guestKey; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-journal-text text-primary me-2"></i> 
                    Booking History: <?php echo htmlspecialchars($guest['guest_name']); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Dates</th>
                                <th>Room & Occupancy</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $bk): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="small fw-semibold"><?php echo date('d M', strtotime($bk['check_in'])); ?> &rarr; <?php echo date('d M Y', strtotime($bk['check_out'])); ?></div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Booked: <?php echo date('d M Y', strtotime($bk['created_at'])); ?></div>
                                </td>
                                <td>
                                    <div class="small fw-semibold"><?php echo htmlspecialchars($bk['category_name'] ?? 'Unknown Room'); ?></div>
                                    <div class="text-muted" style="font-size: 0.75rem;"><?php echo $bk['adults']; ?> Adults, <?php echo $bk['kids']; ?> Kids</div>
                                </td>
                                <td>
                                    <?php 
                                        $s = $bk['status'];
                                        $badgeClass = 'secondary';
                                        if ($s == 'new') $badgeClass = 'danger';
                                        if ($s == 'contacted') $badgeClass = 'warning text-dark';
                                        if ($s == 'confirmed') $badgeClass = 'success';
                                    ?>
                                    <span class="badge bg-<?php echo $badgeClass; ?>"><?php echo strtoupper($s); ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php require_once __DIR__.'/includes/footer.php'; ?>
