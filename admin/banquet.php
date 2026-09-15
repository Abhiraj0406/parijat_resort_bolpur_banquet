<?php
// admin/banquet.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';
$uploadDir = __DIR__.'/../assets/uploads/banquet/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Handle Delete/Status
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM banquet_details WHERE id = :id");
            if ($stmt->execute([':id' => $id])) {
                $successMsg = "Banquet hall entry deleted successfully.";
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    } elseif ($action === 'toggle_status') {
        try {
            $stmt = $pdo->prepare("UPDATE banquet_details SET status = IF(status='active', 'inactive', 'active') WHERE id = :id");
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
        $id           = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $name         = trim($_POST['name'] ?? '');
        $description  = trim($_POST['description'] ?? '');
        $capacity     = (int)($_POST['capacity'] ?? 0);
        $price        = (float)($_POST['price'] ?? 0);
        $pricing_info = trim($_POST['pricing_info'] ?? '');
        $facilities   = trim($_POST['facilities'] ?? '');
        $event_types  = trim($_POST['event_types'] ?? '');
        $image_path   = trim($_POST['existing_image'] ?? 'image/gallery/02.jpg');

        // Handle Image Upload if provided
        if (isset($_FILES['hall_image']) && $_FILES['hall_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['hall_image']['tmp_name'];
            $fileSize = $_FILES['hall_image']['size'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fileTmp);
            finfo_close($finfo);

            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($mimeType, $allowedMimes) && $fileSize <= 5 * 1024 * 1024) {
                $ext = ($mimeType === 'image/png') ? 'png' : (($mimeType === 'image/webp') ? 'webp' : 'jpg');
                $newFileName = 'banquet_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $destination = $uploadDir . $newFileName;
                if (move_uploaded_file($fileTmp, $destination)) {
                    $image_path = 'assets/uploads/banquet/' . $newFileName;
                }
            }
        }

        if (empty($name)) {
            $errorMsg = 'Hall name is required.';
        } else {
            try {
                if ($id > 0) {
                    $sql = "UPDATE banquet_details SET 
                            name=:name, description=:desc, capacity=:cap, price=:price, 
                            pricing_info=:pinfo, facilities=:fac, event_types=:events, image_path=:img 
                            WHERE id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':name'   => $name,
                        ':desc'   => $description,
                        ':cap'    => $capacity,
                        ':price'  => $price,
                        ':pinfo'  => $pricing_info,
                        ':fac'    => $facilities,
                        ':events' => $event_types,
                        ':img'    => $image_path,
                        ':id'     => $id
                    ]);
                    $successMsg = "Banquet hall details updated successfully.";
                } else {
                    $sql = "INSERT INTO banquet_details 
                            (name, description, capacity, price, pricing_info, facilities, event_types, image_path, status) 
                            VALUES (:name, :desc, :cap, :price, :pinfo, :fac, :events, :img, 'active')";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':name'   => $name,
                        ':desc'   => $description,
                        ':cap'    => $capacity,
                        ':price'  => $price,
                        ':pinfo'  => $pricing_info,
                        ':fac'    => $facilities,
                        ':events' => $event_types,
                        ':img'    => $image_path
                    ]);
                    $successMsg = "Banquet hall added successfully.";
                }
            } catch (PDOException $e) {
                $errorMsg = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Fetch all items
$stmt = $pdo->query("SELECT * FROM banquet_details ORDER BY id ASC");
$items = $stmt->fetchAll();

// Edit mode setup
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $editMode = true;
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM banquet_details WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $editData = $stmt->fetch();
}

$csrfToken = generate_csrf_token();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Banquet &amp; Conference Hall Management</h2>
        <p class="text-muted mb-0">Phase 9: Manage hall information, facilities, images, and event types without inventing pricing</p>
    </div>
    <?php if ($editMode): ?>
        <a href="banquet.php" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i> Cancel Edit</a>
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
                <h5 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-primary"></i><?php echo $editMode ? 'Edit Banquet Hall' : 'Add Banquet / Conference Venue'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="banquet.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    <?php if ($editMode): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($editData['image_path'] ?? ''); ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hall Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?php echo $editMode ? htmlspecialchars($editData['name']) : ''; ?>" placeholder="e.g. Grand Parijat Banquet & Conference Hall" required>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Guest Capacity</label>
                            <input type="number" class="form-control" name="capacity" value="<?php echo $editMode ? $editData['capacity'] : '0'; ?>" placeholder="e.g. 150">
                            <small class="text-muted">Enter 0 to show "Capacity on Request".</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Base Tariff (₹)</label>
                            <input type="number" step="0.01" class="form-control" name="price" value="<?php echo $editMode ? $editData['price'] : '0.00'; ?>" placeholder="0.00">
                            <small class="text-muted">Leave at 0 for "Tariff on Request".</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pricing / Tariff Note</label>
                        <input type="text" class="form-control" name="pricing_info" value="<?php echo $editMode ? htmlspecialchars($editData['pricing_info'] ?? '') : 'Tariff on Request / Customized Package'; ?>" placeholder="e.g. Tariff on Request / Customized Package">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Overview of the venue ambiance, architecture, and suitability..."><?php echo $editMode ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Facilities (Comma Separated)</label>
                        <textarea class="form-control" name="facilities" rows="2" placeholder="e.g. Central AC, Audio-Visual Setup, Stage, Dedicated Dining, Parking"><?php echo $editMode ? htmlspecialchars($editData['facilities'] ?? '') : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Event Types Supported (Comma Separated)</label>
                        <textarea class="form-control" name="event_types" rows="2" placeholder="e.g. Weddings, Receptions, Corporate Conferences, Seminars, Family Get-Togethers"><?php echo $editMode ? htmlspecialchars($editData['event_types'] ?? '') : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Venue Image</label>
                        <input type="file" class="form-control" name="hall_image" accept=".jpg,.jpeg,.png,.webp">
                        <?php if ($editMode && !empty($editData['image_path'])): ?>
                            <div class="mt-2 small text-muted">
                                Current Image: <code><?php echo htmlspecialchars($editData['image_path']); ?></code>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i> <?php echo $editMode ? 'Update Banquet Hall' : 'Save Banquet Hall'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Table Col -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-card-checklist me-2 text-primary"></i>Configured Banquet Venues</h5>
                <span class="badge bg-primary"><?php echo count($items); ?> Venues</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Venue</th>
                                <th>Capacity</th>
                                <th>Tariff Info</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($items) > 0): ?>
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($item['name']); ?></div>
                                        <small class="text-muted d-block"><?php echo htmlspecialchars(substr($item['description'], 0, 75)); ?>...</small>
                                        <?php if (!empty($item['event_types'])): ?>
                                            <div class="small text-primary mt-1"><i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($item['event_types']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($item['capacity'] > 0): ?>
                                            <strong><?php echo $item['capacity']; ?> Persons</strong>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary border">On Request</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($item['price'] > 0): ?>
                                            <strong class="text-dark">₹<?php echo number_format($item['price'], 2); ?></strong>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle">
                                                <?php echo htmlspecialchars($item['pricing_info'] ?: 'On Request'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="banquet.php?action=toggle_status&id=<?php echo $item['id']; ?>" class="badge text-decoration-none bg-<?php echo $item['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($item['status']); ?>
                                        </a>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="banquet.php?edit=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                        <a href="banquet.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this banquet venue?');" title="Delete"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-4">No banquet halls found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
