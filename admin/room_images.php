<?php
// admin/room_images.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';
$uploadDir = __DIR__.'/../assets/uploads/rooms/';

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
            $stmt = $pdo->prepare("SELECT image_path FROM room_images WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $img = $stmt->fetch();
            
            if ($img) {
                // Delete from DB
                $delStmt = $pdo->prepare("DELETE FROM room_images WHERE id = :id");
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
        $category_id = (int)($_POST['category_id'] ?? 0);
        $alt_text = trim($_POST['alt_text'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        
        if ($category_id === 0) {
            $errorMsg = 'Room category is required.';
        } elseif (!isset($_FILES['room_image']) || $_FILES['room_image']['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = 'Please select a valid image to upload.';
        } else {
            // Secure file upload validation
            $fileTmp = $_FILES['room_image']['tmp_name'];
            $fileSize = $_FILES['room_image']['size'];
            
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
                $dbPath = 'assets/uploads/rooms/' . $newFileName;
                
                if (move_uploaded_file($fileTmp, $destination)) {
                    // Insert into DB
                    try {
                        $sql = "INSERT INTO room_images (category_id, image_path, alt_text, sort_order) VALUES (:cat, :path, :alt, :sort)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([':cat' => $category_id, ':path' => $dbPath, ':alt' => $alt_text, ':sort' => $sort_order]);
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

// Fetch all categories for dropdown
$stmt = $pdo->query("SELECT id, name FROM room_categories ORDER BY name");
$categories = $stmt->fetchAll();

// Fetch all images
$sql = "SELECT i.*, c.name as category_name 
        FROM room_images i 
        JOIN room_categories c ON i.category_id = c.id 
        ORDER BY c.name, i.sort_order ASC, i.id DESC";
$stmt = $pdo->query($sql);
$images = $stmt->fetchAll();

$csrfToken = generate_csrf_token();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Room Images</h2>
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
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-image me-2 text-primary"></i>Upload New Image</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="room_images.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Room Category</label>
                        <select class="form-control" name="category_id" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Image File (JPG, PNG, WebP) - Max 5MB</label>
                        <input type="file" class="form-control" name="room_image" accept=".jpg,.jpeg,.png,.webp" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alt Text (for SEO & Accessibility)</label>
                        <input type="text" class="form-control" name="alt_text" placeholder="e.g. Luxury AC Room view">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" value="0">
                        <small class="text-muted">Lower numbers appear first.</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold"><i class="bi bi-upload me-1"></i> Upload Image</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Table/Grid Col -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <?php if (count($images) > 0): ?>
                        <?php foreach ($images as $img): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="../<?php echo htmlspecialchars($img['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($img['alt_text']); ?>" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1"><?php echo htmlspecialchars($img['category_name']); ?></h6>
                                    <p class="card-text small text-muted mb-2">Order: <?php echo $img['sort_order']; ?></p>
                                    <a href="room_images.php?action=delete&id=<?php echo $img['id']; ?>" class="btn btn-sm btn-danger btn-block" onclick="return confirm('Delete this image permanently?');"><i class="fas fa-trash"></i> Delete</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-4">No images found. Please upload some.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
