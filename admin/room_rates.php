<?php
// admin/room_rates.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';

// Handle Delete Rate
if (isset($_GET['action']) && isset($_GET['id'])) {
    if ($_GET['action'] === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM room_rates WHERE id = :id");
            if ($stmt->execute([':id' => (int)$_GET['id']])) {
                $successMsg = "Rate deleted successfully.";
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
}

// Handle Form Submission (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Invalid CSRF token.';
    } else {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $category_id = (int)($_POST['category_id'] ?? 0);
        $rate_title = trim($_POST['rate_title'] ?? 'Standard Tariff');
        $rate_type = $_POST['rate_type'] ?? 'base';
        $rate_ac = (float)($_POST['rate_ac'] ?? 0);
        $rate_non_ac = (float)($_POST['rate_non_ac'] ?? 0);
        $season_start = !empty($_POST['season_start']) ? $_POST['season_start'] : null;
        $season_end = !empty($_POST['season_end']) ? $_POST['season_end'] : null;

        // Backward compatibility rate column
        $rate = $rate_ac > 0 ? $rate_ac : $rate_non_ac;

        if ($category_id === 0) {
            $errorMsg = 'Please select a room category.';
        } elseif ($rate_ac <= 0 && $rate_non_ac <= 0) {
            $errorMsg = 'Please enter at least one valid rate (AC or Non-AC).';
        } else {
            try {
                if ($id > 0) {
                    // Update
                    $sql = "UPDATE room_rates SET 
                            category_id=:cat, rate_title=:title, rate_type=:rtype, 
                            rate_ac=:ac, rate_non_ac=:non_ac, rate=:rate, 
                            season_start=:start, season_end=:end 
                            WHERE id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':cat' => $category_id,
                        ':title' => $rate_title,
                        ':rtype' => $rate_type,
                        ':ac' => $rate_ac,
                        ':non_ac' => $rate_non_ac,
                        ':rate' => $rate,
                        ':start' => $season_start,
                        ':end' => $season_end,
                        ':id' => $id
                    ]);
                    $successMsg = "Tariff / Rate updated successfully.";
                } else {
                    // Insert
                    $sql = "INSERT INTO room_rates 
                            (category_id, rate_title, rate_type, rate_ac, rate_non_ac, rate, season_start, season_end) 
                            VALUES (:cat, :title, :rtype, :ac, :non_ac, :rate, :start, :end)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':cat' => $category_id,
                        ':title' => $rate_title,
                        ':rtype' => $rate_type,
                        ':ac' => $rate_ac,
                        ':non_ac' => $rate_non_ac,
                        ':rate' => $rate,
                        ':start' => $season_start,
                        ':end' => $season_end
                    ]);
                    $successMsg = "Tariff / Rate added successfully.";
                }
            } catch (PDOException $e) {
                $errorMsg = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Fetch all categories for dropdown
$stmt = $pdo->query("SELECT id, name, total_rooms, is_convertible_ac FROM room_categories WHERE status='active' ORDER BY id ASC");
$categories = $stmt->fetchAll();

// Fetch all rates with category names
$sql = "SELECT r.*, c.name as category_name, c.is_convertible_ac 
        FROM room_rates r 
        JOIN room_categories c ON r.category_id = c.id 
        ORDER BY c.id ASC, r.rate_type ASC, r.season_start ASC";
$stmt = $pdo->query($sql);
$rates = $stmt->fetchAll();

// Edit mode setup
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $editMode = true;
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM room_rates WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $editData = $stmt->fetch();
}

$csrfToken = generate_csrf_token();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Room Rates & Seasonal Tariffs</h2>
        <p class="text-muted mb-0">Manage Base, Peak Season & Long Weekend tariffs for AC and Non-AC bookings</p>
    </div>
    <?php if ($editMode): ?>
        <a href="room_rates.php" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i> Cancel Edit</a>
    <?php endif; ?>
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

<div class="row">
    <!-- Form Col -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-tag-fill me-2 text-primary"></i><?php echo $editMode ? 'Edit Tariff / Rate' : 'Set New Tariff / Rate'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="room_rates.php">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    <?php if ($editMode): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Room Category <span class="text-danger">*</span></label>
                        <select class="form-select" name="category_id" required>
                            <option value="">-- Select Room Category --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                    <?php echo ($editMode && $editData['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?> (<?php echo $cat['total_rooms']; ?> Rooms)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tariff Title / Label</label>
                        <input type="text" class="form-control" name="rate_title" placeholder="e.g. Regular Tariff, Durga Puja / Winter Special, Long Weekend" value="<?php echo $editMode ? htmlspecialchars($editData['rate_title'] ?? '') : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tariff Type</label>
                        <select class="form-select" name="rate_type" id="rateTypeSelect">
                            <option value="base" <?php echo ($editMode && ($editData['rate_type'] ?? '') == 'base') ? 'selected' : ''; ?>>Regular / Base Rate (All Year)</option>
                            <option value="season" <?php echo ($editMode && ($editData['rate_type'] ?? '') == 'season') ? 'selected' : ''; ?>>Seasonal Rate (Date Range)</option>
                            <option value="long_weekend" <?php echo ($editMode && ($editData['rate_type'] ?? '') == 'long_weekend') ? 'selected' : ''; ?>>Long Weekend / Festive Rush</option>
                        </select>
                    </div>

                    <div class="p-3 mb-3 bg-light rounded border">
                        <div class="fw-semibold mb-2 text-dark"><i class="bi bi-cash-stack me-1"></i> Convertible Pricing (Per Night)</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-primary">AC Rate (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" class="form-control" name="rate_ac" placeholder="0.00" value="<?php echo $editMode ? ($editData['rate_ac'] ?? $editData['rate']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-success">Non-AC Rate (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" class="form-control" name="rate_non_ac" placeholder="0.00" value="<?php echo $editMode ? ($editData['rate_non_ac'] ?? '') : ''; ?>">
                                </div>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">Enter prices as per management policy. Leave at 0 if prices are on request.</small>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Effective Start Date</label>
                            <input type="date" class="form-control" name="season_start" value="<?php echo $editMode ? $editData['season_start'] : ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Effective End Date</label>
                            <input type="date" class="form-control" name="season_end" value="<?php echo $editMode ? $editData['season_end'] : ''; ?>">
                        </div>
                    </div>
                    <small class="text-muted d-block mb-3">Leave dates blank for standard year-round base rates.</small>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-save me-1"></i> <?php echo $editMode ? 'Update Tariff' : 'Save Tariff'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Table Col -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-card-checklist me-2 text-primary"></i>Configured Room Tariffs</h5>
                <span class="badge bg-primary"><?php echo count($rates); ?> Tariff Rules</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Category & Tariff</th>
                                <th>Tariff Type</th>
                                <th>AC Rate</th>
                                <th>Non-AC Rate</th>
                                <th>Validity Period</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($rates) > 0): ?>
                                <?php foreach ($rates as $r): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($r['category_name']); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($r['rate_title'] ?? 'Standard Tariff'); ?></div>
                                    </td>
                                    <td>
                                        <?php if (($r['rate_type'] ?? '') === 'long_weekend'): ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-calendar-event me-1"></i>Long Weekend</span>
                                        <?php elseif (($r['rate_type'] ?? '') === 'season'): ?>
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-sun me-1"></i>Seasonal Rate</span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-calendar-check me-1"></i>Base Rate</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($r['rate_ac']) && $r['rate_ac'] > 0): ?>
                                            <strong class="text-primary">₹<?php echo number_format($r['rate_ac'], 2); ?></strong>
                                        <?php else: ?>
                                            <span class="text-muted small">On Request</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($r['rate_non_ac']) && $r['rate_non_ac'] > 0): ?>
                                            <strong class="text-success">₹<?php echo number_format($r['rate_non_ac'], 2); ?></strong>
                                        <?php else: ?>
                                            <span class="text-muted small">On Request</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($r['season_start'] && $r['season_end']): ?>
                                            <span class="small text-dark font-monospace">
                                                <?php echo date('d M Y', strtotime($r['season_start'])); ?> &rarr; <?php echo date('d M Y', strtotime($r['season_end'])); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="small text-muted">All Year (Standard)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="room_rates.php?edit=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                        <a href="room_rates.php?action=delete&id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this tariff?');" title="Delete"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-tag text-muted fs-1 d-block mb-2"></i>
                                        <p class="text-muted mb-1">No tariffs have been entered yet.</p>
                                        <small class="text-secondary">Admin can enter room rates above at any time. Prices will automatically update across the website.</small>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
