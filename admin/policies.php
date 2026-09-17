<?php
// admin/policies.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';

// Handle Form Submission (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Invalid CSRF token.';
    } else {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $title = trim($_POST['title'] ?? '');
        $type = $_POST['type'] ?? 'other';
        $content = trim($_POST['content'] ?? '');
        $status = $_POST['status'] ?? 'active';

        if (empty($title) || empty($content)) {
            $errorMsg = 'Title and content are required.';
        } else {
            try {
                if ($id > 0) {
                    $sql = "UPDATE policies SET title=:title, type=:type, content=:content, status=:status WHERE id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':title'=>$title, ':type'=>$type, ':content'=>$content, ':status'=>$status, ':id'=>$id]);
                    $successMsg = "Policy updated successfully.";
                } else {
                    $sql = "INSERT INTO policies (title, type, content, status) VALUES (:title, :type, :content, :status)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':title'=>$title, ':type'=>$type, ':content'=>$content, ':status'=>$status]);
                    $successMsg = "Policy added successfully.";
                }
            } catch (PDOException $e) {
                $errorMsg = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Handle Delete
if (isset($_GET['action']) && isset($_GET['id'])) {
    if ($_GET['action'] === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM policies WHERE id = :id");
            if ($stmt->execute([':id' => (int)$_GET['id']])) {
                $successMsg = "Policy deleted successfully.";
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch all
$stmt = $pdo->query("SELECT * FROM policies ORDER BY type, title");
$policies = $stmt->fetchAll();

// Edit mode setup
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $editMode = true;
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM policies WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $editData = $stmt->fetch();
}

$csrfToken = generate_csrf_token();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Policies & Rules</h2>
    <?php if ($editMode): ?>
        <a href="policies.php" class="btn btn-secondary">Cancel Edit</a>
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
                <h5 class="mb-0 fw-bold"><i class="bi bi-shield-check me-2 text-primary"></i><?php echo $editMode ? 'Edit Policy' : 'Add New Policy'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="policies.php">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    <?php if ($editMode): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" class="form-control" name="title" value="<?php echo $editMode ? htmlspecialchars($editData['title']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Type</label>
                        <select class="form-select" name="type" required>
                            <option value="privacy" <?php echo ($editMode && $editData['type'] == 'privacy') ? 'selected' : ''; ?>>Privacy Policy</option>
                            <option value="terms" <?php echo ($editMode && $editData['type'] == 'terms') ? 'selected' : ''; ?>>Terms & Conditions</option>
                            <option value="cancellation" <?php echo ($editMode && $editData['type'] == 'cancellation') ? 'selected' : ''; ?>>Cancellation Policy</option>
                            <option value="other" <?php echo ($editMode && $editData['type'] == 'other') ? 'selected' : ''; ?>>Other Rules</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Content (HTML allowed)</label>
                        <textarea class="form-control" name="content" rows="6" required><?php echo $editMode ? htmlspecialchars($editData['content']) : ''; ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status">
                            <option value="active" <?php echo ($editMode && $editData['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($editMode && $editData['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold"><i class="bi bi-save me-1"></i> <?php echo $editMode ? 'Update Policy' : 'Add Policy'; ?></button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Table Col -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2 text-primary"></i>Configured Policies</h5>
                <span class="badge bg-primary"><?php echo count($policies); ?> Policies</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Type</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($policies) > 0): ?>
                                <?php foreach ($policies as $pol): ?>
                                <tr>
                                    <td class="ps-3"><span class="badge bg-info text-dark rounded-pill px-3"><?php echo ucfirst($pol['type']); ?></span></td>
                                    <td class="fw-semibold text-dark"><?php echo htmlspecialchars($pol['title']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $pol['status'] == 'active' ? 'success' : 'secondary'; ?> rounded-pill px-3">
                                            <?php echo ucfirst($pol['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="policies.php?edit=<?php echo $pol['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
                                        <a href="policies.php?action=delete&id=<?php echo $pol['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this policy?');"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4">No policies found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
