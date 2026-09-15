<?php
// admin/gallery.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';
$uploadDir = __DIR__.'/../assets/uploads/gallery/';

// Ensure upload directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Handle Delete Image
if (isset($_GET['action']) && isset($_GET['id'])) {
    if ($_GET['action'] === 'delete') {
        $id = (int)$_GET['id'];
        try {
            // First get the image path so we can delete the file
            $stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $img = $stmt->fetch();
            
            if ($img) {
                // Delete from DB
                $delStmt = $pdo->prepare("DELETE FROM gallery WHERE id = :id");
                if ($delStmt->execute([':id' => $id])) {
                    // Delete actual file
                    $filePath = __DIR__.'/../' . ltrim($img['image_path'], '/');
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $successMsg = "Image deleted successfully.";
                }
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
}

// Handle Image Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Invalid CSRF token.';
    } else {
        $category = trim($_POST['category'] ?? '');
        $caption = trim($_POST['caption'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        
        if (!isset($_FILES['gallery_image']) || $_FILES['gallery_image']['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = 'Please select a valid image to upload.';
        } else {
            // Secure file upload validation
            $fileTmp = $_FILES['gallery_image']['tmp_name'];
            $fileSize = $_FILES['gallery_image']['size'];
            
            // Validate MIME type securely
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fileTmp);
            finfo_close($finfo);
            
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($mimeType, $allowedMimes)) {
                $errorMsg = 'Invalid file type. Only JPG, PNG, and WebP are allowed.';
            } elseif ($fileSize > 5 * 1024 * 1024) { // 5MB limit
                $errorMsg = 'File is too large. Maximum size is 5MB.';
            } else {
                // Generate a unique, secure filename
                $ext = 'jpg';
                if ($mimeType === 'image/png') $ext = 'png';
                if ($mimeType === 'image/webp') $ext = 'webp';
                
                $newFileName = bin2hex(random_bytes(10)) . '_' . time() . '.' . $ext;
                $destination = $uploadDir . $newFileName;
                $dbPath = 'assets/uploads/gallery/' . $newFileName;
                
                if (move_uploaded_file($fileTmp, $destination)) {
                    // Insert into DB
                    try {
                        $sql = "INSERT INTO gallery (image_path, caption, category, sort_order) VALUES (:path, :cap, :cat, :sort)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([':path' => $dbPath, ':cap' => $caption, ':cat' => $category, ':sort' => $sort_order]);
                        $successMsg = 'Image uploaded successfully.';
                    } catch (PDOException $e) {
                        unlink($destination); // rollback file upload
                        $errorMsg = "Database error: " . $e->getMessage();
                    }
                } else {
                    $errorMsg = 'Failed to move uploaded file.';
                }
            }
        }
    }
}

// Fetch all images
$sql = "SELECT * FROM gallery ORDER BY category, sort_order ASC, id DESC";
$stmt = $pdo->query($sql);
$images = $stmt->fetchAll();

$csrfToken = generate_csrf_token();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Photo Gallery</h2>
        <p class="text-muted mb-0">Manage resort images displayed on the frontend gallery section</p>
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

<div class="row">
    <!-- Form Col -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-cloud-arrow-up me-2 text-primary"></i>Upload New Photo</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="gallery.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category (Optional)</label>
                        <input type="text" class="form-control" name="category" placeholder="e.g. Exterior, Dining, Pool" list="categoryList">
                        <datalist id="categoryList">
                            <option value="Exterior">
                            <option value="Rooms">
                            <option value="Dining">
                            <option value="Events">
                        </datalist>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Image File (JPG, PNG, WebP) - Max 5MB <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="gallery_image" accept=".jpg,.jpeg,.png,.webp" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Caption</label>
                        <input type="text" class="form-control" name="caption" placeholder="Short description for the image">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" value="0">
                        <small class="text-muted">Lower numbers appear first.</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-upload me-2"></i> Upload Image</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Table/Grid Col -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-images me-2 text-primary"></i>Uploaded Images</h5>
            </div>
            <div class="card-body bg-light">
                <div class="row g-3">
                    <?php if (count($images) > 0): ?>
                        <?php foreach ($images as $img): ?>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <img src="../<?php echo htmlspecialchars($img['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($img['caption']); ?>" style="height: 180px; object-fit: cover;">
                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <?php if ($img['category']): ?>
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2 py-1"><?php echo htmlspecialchars($img['category']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="card-text small text-muted mb-3 flex-grow-1"><?php echo htmlspecialchars($img['caption'] ?: 'No caption'); ?></p>
                                    <a href="gallery.php?action=delete&id=<?php echo $img['id']; ?>" class="btn btn-sm btn-outline-danger w-100 mt-auto" onclick="return confirm('Delete this image permanently?');"><i class="bi bi-trash3 me-1"></i> Delete</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="bi bi-images fs-1 d-block mb-3"></i>
                            No images found in the gallery.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
