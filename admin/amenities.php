<?php
// admin/amenities.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';

// Handle Delete/Status
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM amenities WHERE id = :id");
            if ($stmt->execute([':id' => $id])) {
                $successMsg = "Amenity deleted successfully.";
            }
        } catch (PDOException $e) {
            $errorMsg = "Cannot delete amenity as it is linked to room categories.";
        }
    } elseif ($action === 'toggle_status') {
        try {
            $stmt = $pdo->prepare("UPDATE amenities SET status = IF(status='active', 'inactive', 'active') WHERE id = :id");
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
        $icon_class = trim($_POST['icon_class'] ?? '');

        if (empty($name)) {
            $errorMsg = 'Name is required.';
        } else {
            try {
                if ($id > 0) {
                    // Update
                    $sql = "UPDATE amenities SET name=:name, description=:desc, icon_class=:icon WHERE id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':name'=>$name, ':desc'=>$description, ':icon'=>$icon_class, ':id'=>$id]);
                    $successMsg = "Amenity updated successfully.";
                } else {
                    // Insert
                    $sql = "INSERT INTO amenities (name, description, icon_class) VALUES (:name, :desc, :icon)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':name'=>$name, ':desc'=>$description, ':icon'=>$icon_class]);
                    $successMsg = "Amenity added successfully.";
                }
            } catch (PDOException $e) {
                $errorMsg = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Fetch all amenities
$stmt = $pdo->query("SELECT * FROM amenities ORDER BY name");
$amenities = $stmt->fetchAll();

// Edit mode setup
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $editMode = true;
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM amenities WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $editData = $stmt->fetch();
}

$csrfToken = generate_csrf_token();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Amenities</h2>
    <?php if ($editMode): ?>
        <a href="amenities.php" class="btn btn-secondary">Cancel Edit</a>
    <?php endif; ?>
</div>

<?php if ($errorMsg): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
<?php endif; ?>
<?php if ($successMsg): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
<?php endif; ?>

<div class="row">
    <!-- Form Col -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-star me-2 text-primary"></i><?php echo $editMode ? 'Edit Amenity' : 'Add New Amenity'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="amenities.php">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    <?php if ($editMode): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Amenity Name</label>
                        <input type="text" class="form-control" name="name" value="<?php echo $editMode ? htmlspecialchars($editData['name']) : ''; ?>" required placeholder="e.g. Free Wi-Fi">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Icon Class (Bootstrap Icons)</label>
                        <input type="text" class="form-control" name="icon_class" value="<?php echo $editMode ? htmlspecialchars($editData['icon_class']) : ''; ?>" placeholder="e.g. bi-wifi">
                        <small class="text-muted"><a href="https://icons.getbootstrap.com/" target="_blank">Find icons here</a></small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" name="description" rows="3"><?php echo $editMode ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold"><i class="bi bi-save me-1"></i> <?php echo $editMode ? 'Update Amenity' : 'Add Amenity'; ?></button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Table Col -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2 text-primary"></i>Configured Amenities</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Icon</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($amenities) > 0): ?>
                                <?php foreach ($amenities as $a): ?>
                                <tr>
                                    <td><i class="bi <?php echo htmlspecialchars($a['icon_class']); ?> text-primary fs-5"></i></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($a['name']); ?></strong>
                                    </td>
                                    <td>
                                        <a href="amenities.php?action=toggle_status&id=<?php echo $a['id']; ?>" class="badge bg-<?php echo $a['status'] == 'active' ? 'success' : 'secondary'; ?> text-white text-decoration-none">
                                            <?php echo ucfirst($a['status']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="amenities.php?edit=<?php echo $a['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
                                        <a href="amenities.php?action=delete&id=<?php echo $a['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this amenity?');"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4">No amenities found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
