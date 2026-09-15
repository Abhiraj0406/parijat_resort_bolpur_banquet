<?php
// admin/room_categories.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';

// Handle Delete/Status Toggle
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM room_categories WHERE id = :id");
            if ($stmt->execute([':id' => $id])) {
                $successMsg = "Category deleted successfully.";
            }
        } catch (PDOException $e) {
            $errorMsg = "Cannot delete category as it may have linked bookings or rates.";
        }
    } elseif ($action === 'toggle_status') {
        try {
            $stmt = $pdo->prepare("UPDATE room_categories SET status = IF(status='active', 'inactive', 'active') WHERE id = :id");
            if ($stmt->execute([':id' => $id])) {
                $successMsg = "Status updated successfully.";
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
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $facilities = trim($_POST['facilities'] ?? '');
        $total_rooms = (int)($_POST['total_rooms'] ?? 1);
        $ac = isset($_POST['ac']) ? 1 : 0;
        $has_balcony = isset($_POST['has_balcony']) ? 1 : 0;
        $is_convertible_ac = isset($_POST['is_convertible_ac']) ? 1 : 0;
        $max_occupancy = (int)($_POST['max_occupancy'] ?? 2);
        $adult_capacity = (int)($_POST['adult_capacity'] ?? 2);
        $kids_capacity = (int)($_POST['kids_capacity'] ?? 0);
        $extra_person_charge = (float)($_POST['extra_person_charge'] ?? 0);
        $status = $_POST['status'] ?? 'active';

        if (empty($name)) {
            $errorMsg = 'Category Name is required.';
        } else {
            try {
                if ($id > 0) {
                    // Update
                    $sql = "UPDATE room_categories SET 
                            name=:name, description=:desc, facilities=:facilities, total_rooms=:total, 
                            ac=:ac, has_balcony=:balcony, is_convertible_ac=:conv, 
                            max_occupancy=:max, adult_capacity=:adult, kids_capacity=:kids, 
                            extra_person_charge=:extra, status=:status 
                            WHERE id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':name' => $name, ':desc' => $description, ':facilities' => $facilities,
                        ':total' => $total_rooms, ':ac' => $ac, ':balcony' => $has_balcony,
                        ':conv' => $is_convertible_ac, ':max' => $max_occupancy,
                        ':adult' => $adult_capacity, ':kids' => $kids_capacity,
                        ':extra' => $extra_person_charge, ':status' => $status, ':id' => $id
                    ]);
                    $successMsg = "Category updated successfully.";
                } else {
                    // Insert
                    $sql = "INSERT INTO room_categories 
                            (name, description, facilities, total_rooms, ac, has_balcony, is_convertible_ac, max_occupancy, adult_capacity, kids_capacity, extra_person_charge, status) 
                            VALUES (:name, :desc, :facilities, :total, :ac, :balcony, :conv, :max, :adult, :kids, :extra, :status)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':name' => $name, ':desc' => $description, ':facilities' => $facilities,
                        ':total' => $total_rooms, ':ac' => $ac, ':balcony' => $has_balcony,
                        ':conv' => $is_convertible_ac, ':max' => $max_occupancy,
                        ':adult' => $adult_capacity, ':kids' => $kids_capacity,
                        ':extra' => $extra_person_charge, ':status' => $status
                    ]);
                    $successMsg = "Category added successfully.";
                }
            } catch (PDOException $e) {
                $errorMsg = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Fetch all categories
$stmt = $pdo->query("SELECT * FROM room_categories ORDER BY id ASC");
$categories = $stmt->fetchAll();

// Edit mode setup
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $editMode = true;
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM room_categories WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $editData = $stmt->fetch();
}

$csrfToken = generate_csrf_token();
$totalRoomsSum = array_sum(array_column($categories, 'total_rooms'));
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Room Categories</h2>
        <p class="text-muted mb-0">Total Resort Rooms: <strong class="text-primary font-weight-bold"><?php echo $totalRoomsSum; ?> / 19</strong> rooms configured</p>
    </div>
    <?php if ($editMode): ?>
        <a href="room_categories.php" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i> Cancel Edit</a>
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
                <h5 class="mb-0 fw-bold"><i class="bi bi-door-open me-2 text-primary"></i><?php echo $editMode ? 'Edit Category' : 'Add New Category'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="room_categories.php">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    <?php if ($editMode): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?php echo $editMode ? htmlspecialchars($editData['name']) : ''; ?>" placeholder="e.g. Deluxe Cottage Rooms with Balcony" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Brief appealing description of this room type..."><?php echo $editMode ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Facilities (comma-separated tags)</label>
                        <textarea class="form-control" name="facilities" rows="2" placeholder="e.g. Balcony, Free Wi-Fi, King Bed, LED TV, Geyser, Pool Access"><?php echo $editMode ? htmlspecialchars($editData['facilities'] ?? '') : ''; ?></textarea>
                        <small class="text-muted">These tags will appear as facility badges on the website.</small>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Number of Rooms <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="total_rooms" min="1" value="<?php echo $editMode ? $editData['total_rooms'] : '1'; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Extra Person (₹/night)</label>
                            <input type="number" step="0.01" class="form-control" name="extra_person_charge" value="<?php echo $editMode ? $editData['extra_person_charge'] : '500.00'; ?>">
                        </div>
                    </div>

                    <!-- Convertible & Balcony Features -->
                    <div class="p-3 mb-3 bg-light rounded border">
                        <div class="fw-semibold mb-2 text-dark"><i class="bi bi-gear-fill me-1"></i> Room Configuration</div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="convSwitch" name="is_convertible_ac" <?php echo (!$editMode || !empty($editData['is_convertible_ac'])) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="convSwitch"><strong>Convertible AC / Non-AC</strong> (Bookable as either)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="balconySwitch" name="has_balcony" <?php echo (!$editMode || !empty($editData['has_balcony'])) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="balconySwitch"><strong>Has Private Balcony</strong></label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="acSwitch" name="ac" <?php echo (!$editMode || $editData['ac'] == 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="acSwitch">AC Unit Fitted</label>
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Adults</label>
                            <input type="number" class="form-control" name="adult_capacity" min="1" value="<?php echo $editMode ? $editData['adult_capacity'] : '2'; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kids</label>
                            <input type="number" class="form-control" name="kids_capacity" min="0" value="<?php echo $editMode ? $editData['kids_capacity'] : '1'; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Max Total</label>
                            <input type="number" class="form-control" name="max_occupancy" min="1" value="<?php echo $editMode ? $editData['max_occupancy'] : '3'; ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status">
                            <option value="active" <?php echo ($editMode && $editData['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($editMode && $editData['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i> <?php echo $editMode ? 'Update Room Category' : 'Save Room Category'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Table Col -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-stars me-2 text-primary"></i>Configured Room Categories</h5>
                <span class="badge bg-primary"><?php echo count($categories); ?> Categories</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Category & Features</th>
                                <th class="text-center">Rooms</th>
                                <th>Capacity</th>
                                <th>Extra Person</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($categories) > 0): ?>
                                <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($cat['name']); ?></div>
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                            <?php if (!empty($cat['has_balcony'])): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 11px;"><i class="bi bi-flower2"></i> Balcony</span>
                                            <?php endif; ?>
                                            <?php if (!empty($cat['is_convertible_ac'])): ?>
                                                <span class="badge bg-info-subtle text-info border border-info-subtle" style="font-size: 11px;"><i class="bi bi-arrow-left-right"></i> AC / Non-AC</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-dark fs-6 px-2 py-1"><?php echo $cat['total_rooms']; ?></span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <i class="bi bi-person-fill text-muted"></i> <?php echo $cat['adult_capacity']; ?> Adults, <?php echo $cat['kids_capacity']; ?> Kids<br>
                                            <span class="text-muted">Max: <?php echo $cat['max_occupancy']; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">₹<?php echo number_format($cat['extra_person_charge'], 0); ?></span>
                                    </td>
                                    <td>
                                        <a href="room_categories.php?action=toggle_status&id=<?php echo $cat['id']; ?>" class="badge text-decoration-none bg-<?php echo $cat['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($cat['status']); ?>
                                        </a>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="room_categories.php?edit=<?php echo $cat['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                        <a href="room_categories.php?action=delete&id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this category?');" title="Delete"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center py-4">No categories found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
