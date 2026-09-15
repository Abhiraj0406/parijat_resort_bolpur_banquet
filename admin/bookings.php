<?php
// admin/bookings.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';

// Handle Status Update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    $validStatuses = ['new', 'contacted', 'confirmed', 'cancelled', 'closed'];
    
    if ($action === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM booking_inquiries WHERE id = :id");
            if ($stmt->execute([':id' => $id])) {
                $successMsg = "Booking enquiry deleted successfully.";
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    } elseif (in_array($action, $validStatuses)) {
        try {
            $stmt = $pdo->prepare("UPDATE booking_inquiries SET status = :status WHERE id = :id");
            if ($stmt->execute([':status' => $action, ':id' => $id])) {
                $successMsg = "Booking status updated to " . ucfirst($action) . ".";
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
}

// Filtering & Search
$statusFilter = $_GET['status'] ?? '';
$search = trim($_GET['search'] ?? '');

$whereClauses = [];
$params = [];

if ($statusFilter && in_array($statusFilter, ['new', 'contacted', 'confirmed', 'cancelled', 'closed'])) {
    $whereClauses[] = "b.status = :status";
    $params[':status'] = $statusFilter;
}

if ($search) {
    $whereClauses[] = "(b.guest_name LIKE :search OR b.email LIKE :search OR b.phone LIKE :search)";
    $params[':search'] = "%$search%";
}

$whereSql = '';
if (count($whereClauses) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

// Pagination
$perPage = 20;
$page = isset($_GET['page']) && ctype_digit($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

// Count total
$countSql = "SELECT COUNT(*) FROM booking_inquiries b $whereSql";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$totalRows = (int)$stmt->fetchColumn();
$totalPages = (int)ceil($totalRows / $perPage);

// Fetch Bookings
$sql = "SELECT b.*, c.name as category_name, c.has_balcony, c.is_convertible_ac 
        FROM booking_inquiries b 
        LEFT JOIN room_categories c ON b.category_id = c.id 
        $whereSql 
        ORDER BY b.created_at DESC 
        LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();

// Build query string for pagination links
$queryParams = $_GET;
unset($queryParams['page'], $queryParams['action'], $queryParams['id']);
$queryString = http_build_query($queryParams);
$pageUrl = "bookings.php?" . ($queryString ? $queryString . '&' : '');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Booking Enquiries</h2>
        <p class="text-muted mb-0">Total Enquiries: <strong class="text-primary font-weight-bold"><?php echo $totalRows; ?></strong></p>
    </div>
</div>

<?php if ($errorMsg): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($errorMsg); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if ($successMsg): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($successMsg); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Filter & Search Bar -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="bookings.php" class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" placeholder="Search by name, email, or phone..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="">All Statuses</option>
                    <option value="new" <?php echo $statusFilter === 'new' ? 'selected' : ''; ?>>New</option>
                    <option value="contacted" <?php echo $statusFilter === 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                    <option value="confirmed" <?php echo $statusFilter === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                    <option value="cancelled" <?php echo $statusFilter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    <option value="closed" <?php echo $statusFilter === 'closed' ? 'selected' : ''; ?>>Closed</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i> Filter</button>
                <a href="bookings.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date Received</th>
                        <th>Guest Info</th>
                        <th>Room & AC Preference</th>
                        <th>Dates & Occupancy</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($bookings) > 0): ?>
                        <?php foreach ($bookings as $b): ?>
                        <tr class="<?php echo $b['status'] === 'new' ? 'table-warning' : ''; ?>">
                            <td class="text-nowrap small text-muted">
                                <?php echo date('d M Y', strtotime($b['created_at'])); ?><br>
                                <span class="font-monospace"><?php echo date('h:i A', strtotime($b['created_at'])); ?></span>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($b['guest_name']); ?></strong>
                                <?php if ($b['email'] === 'quick-booking@parijatresortbolpur.com'): ?>
                                    <span class="badge bg-info ms-2 text-dark">Quick Booking</span>
                                <?php endif; ?>
                                <br>
                                <a href="tel:<?php echo htmlspecialchars($b['phone']); ?>" class="text-decoration-none small text-dark"><i class="bi bi-telephone-fill text-secondary me-1"></i> <?php echo htmlspecialchars($b['phone']); ?></a><br>
                                <?php if (!empty($b['whatsapp_number']) && $b['whatsapp_number'] !== $b['phone']): ?>
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $b['whatsapp_number']); ?>" target="_blank" class="text-decoration-none small text-success"><i class="bi bi-whatsapp me-1"></i> <?php echo htmlspecialchars($b['whatsapp_number']); ?></a><br>
                                <?php elseif (!empty($b['whatsapp_number'])): ?>
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $b['phone']); ?>" target="_blank" class="text-decoration-none small text-success"><i class="bi bi-whatsapp me-1"></i> <?php echo htmlspecialchars($b['phone']); ?></a><br>
                                <?php endif; ?>
                                <?php if (!empty($b['email']) && $b['email'] !== 'quick-booking@parijatresortbolpur.com'): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($b['email']); ?>" class="text-decoration-none small text-muted"><i class="bi bi-envelope me-1"></i> <?php echo htmlspecialchars($b['email']); ?></a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($b['email'] === 'quick-booking@parijatresortbolpur.com'): ?>
                                    <div class="fw-semibold text-muted fst-italic">Not Selected (Quick Booking)</div>
                                <?php else: ?>
                                    <div class="fw-semibold text-dark"><?php echo htmlspecialchars($b['category_name'] ?? 'General Enquiry'); ?></div>
                                    <div class="mt-1 d-flex flex-wrap gap-1">
                                        <span class="badge bg-dark-subtle text-dark border border-dark-subtle"><i class="bi bi-door-closed me-1"></i><?php echo !empty($b['number_of_rooms']) ? $b['number_of_rooms'] : 1; ?> Room(s)</span>
                                        <?php if (($b['ac_preference'] ?? '') === 'AC'): ?>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-snow me-1"></i> AC</span>
                                        <?php elseif (($b['ac_preference'] ?? '') === 'Non-AC'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-wind me-1"></i> Non-AC</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Any</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($b['message']) && $b['message'] !== 'Submitted via Quick Booking Form on Homepage.'): ?>
                                        <small class="d-block text-muted mt-1 fst-italic">"<?php echo htmlspecialchars($b['message']); ?>"</small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small fw-semibold">
                                    <i class="bi bi-calendar-event text-primary me-1"></i>
                                    <?php echo date('d M', strtotime($b['check_in'])); ?> &rarr; <?php echo date('d M Y', strtotime($b['check_out'])); ?>
                                </div>
                                <?php if ($b['email'] !== 'quick-booking@parijatresortbolpur.com'): ?>
                                <small class="text-muted">
                                    <?php echo $b['adults']; ?> Adults, <?php echo $b['kids']; ?> Kids
                                    <?php if (!empty($b['extra_persons'])): ?>
                                        | +<?php echo $b['extra_persons']; ?> Extra Bed
                                    <?php endif; ?>
                                </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                    $bgClass = 'secondary';
                                    if ($b['status'] === 'new') $bgClass = 'danger';
                                    if ($b['status'] === 'contacted') $bgClass = 'warning';
                                    if ($b['status'] === 'confirmed') $bgClass = 'success';
                                ?>
                                <span class="badge bg-<?php echo $bgClass; ?> text-uppercase"><?php echo htmlspecialchars($b['status']); ?></span>
                            </td>
                            <td class="text-end text-nowrap">
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Status
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $b['id']; ?>"><i class="bi bi-eye me-2 text-info"></i>View Details</a></li>
                                        <li><a class="dropdown-item" href="bookings.php?action=contacted&id=<?php echo $b['id']; ?>"><i class="bi bi-telephone me-2 text-warning"></i>Mark Contacted</a></li>
                                        <li><a class="dropdown-item" href="bookings.php?action=confirmed&id=<?php echo $b['id']; ?>"><i class="bi bi-check-circle me-2 text-success"></i>Mark Confirmed</a></li>
                                        <li><a class="dropdown-item" href="bookings.php?action=cancelled&id=<?php echo $b['id']; ?>"><i class="bi bi-x-circle me-2 text-danger"></i>Mark Cancelled</a></li>
                                        <li><a class="dropdown-item" href="bookings.php?action=closed&id=<?php echo $b['id']; ?>"><i class="bi bi-archive me-2 text-secondary"></i>Mark Closed</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="bookings.php?action=delete&id=<?php echo $b['id']; ?>" onclick="return confirm('Delete this booking permanently?');"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <!-- View Modal -->
                        <div class="modal fade" id="viewModal<?php echo $b['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $b['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewModalLabel<?php echo $b['id']; ?>">Booking Details (#<?php echo $b['id']; ?>)</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($b['guest_name']); ?></p>
                                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($b['phone']); ?></p>
                                        <?php if (!empty($b['email']) && $b['email'] !== 'quick-booking@parijatresortbolpur.com'): ?>
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($b['email']); ?></p>
                                        <?php endif; ?>
                                        <hr>
                                        <p><strong>Check-in:</strong> <?php echo date('d M Y', strtotime($b['check_in'])); ?></p>
                                        <p><strong>Check-out:</strong> <?php echo date('d M Y', strtotime($b['check_out'])); ?></p>
                                        <?php if ($b['email'] !== 'quick-booking@parijatresortbolpur.com'): ?>
                                            <p><strong>Room:</strong> <?php echo htmlspecialchars($b['category_name']); ?> (<?php echo $b['ac_preference']; ?>)</p>
                                            <p><strong>Occupancy:</strong> <?php echo $b['adults']; ?> Adults, <?php echo $b['kids']; ?> Kids</p>
                                        <?php else: ?>
                                            <p><strong>Booking Type:</strong> Quick Booking (No room selected)</p>
                                        <?php endif; ?>
                                        <?php if (!empty($b['message']) && $b['message'] !== 'Submitted via Quick Booking Form on Homepage.'): ?>
                                            <hr>
                                            <p><strong>Message:</strong><br><?php echo nl2br(htmlspecialchars($b['message'])); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No booking enquiries found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($totalPages > 1): ?>
        <div class="card-footer bg-white">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo $pageUrl . 'page=' . ($page - 1); ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo $pageUrl . 'page=' . $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo $pageUrl . 'page=' . ($page + 1); ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
