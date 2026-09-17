<?php
// admin/seo_settings.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';
$uploadDir = __DIR__.'/../assets/uploads/';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Invalid CSRF token.';
    } else {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $page_name = trim($_POST['page_name'] ?? '');
        $meta_title = trim($_POST['meta_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');
        $meta_keywords = trim($_POST['meta_keywords'] ?? '');
        $canonical_url = trim($_POST['canonical_url'] ?? '');
        $og_title = trim($_POST['og_title'] ?? '');
        $og_description = trim($_POST['og_description'] ?? '');
        
        // Handle OG Image upload
        $og_image_path = $_POST['existing_og_image'] ?? '';
        if (isset($_FILES['og_image']) && $_FILES['og_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['og_image']['tmp_name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fileTmp);
            finfo_close($finfo);
            
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($mimeType, $allowedMimes)) {
                $ext = 'jpg';
                if ($mimeType === 'image/png') $ext = 'png';
                if ($mimeType === 'image/webp') $ext = 'webp';
                
                $newFileName = 'og_' . bin2hex(random_bytes(5)) . '_' . time() . '.' . $ext;
                if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                    $og_image_path = 'assets/uploads/' . $newFileName;
                }
            }
        }

        if (empty($page_name)) {
            $errorMsg = 'Page name/URL identifier is required.';
        } else {
            try {
                if ($id > 0) {
                    $sql = "UPDATE seo_settings SET page_name=:page, meta_title=:title, meta_description=:desc, 
                            meta_keywords=:keys, canonical_url=:curl, og_title=:og_title, 
                            og_description=:og_desc, og_image=:og_img WHERE id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':page'=>$page_name, ':title'=>$meta_title, ':desc'=>$meta_description,
                        ':keys'=>$meta_keywords, ':curl'=>$canonical_url, ':og_title'=>$og_title,
                        ':og_desc'=>$og_description, ':og_img'=>$og_image_path, ':id'=>$id
                    ]);
                    $successMsg = "SEO settings updated successfully.";
                } else {
                    $sql = "INSERT INTO seo_settings (page_name, meta_title, meta_description, meta_keywords, canonical_url, og_title, og_description, og_image) 
                            VALUES (:page, :title, :desc, :keys, :curl, :og_title, :og_desc, :og_img)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':page'=>$page_name, ':title'=>$meta_title, ':desc'=>$meta_description,
                        ':keys'=>$meta_keywords, ':curl'=>$canonical_url, ':og_title'=>$og_title,
                        ':og_desc'=>$og_description, ':og_img'=>$og_image_path
                    ]);
                    $successMsg = "SEO settings added successfully.";
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
            $stmt = $pdo->prepare("DELETE FROM seo_settings WHERE id = :id");
            if ($stmt->execute([':id' => (int)$_GET['id']])) {
                $successMsg = "SEO settings deleted successfully.";
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch all pages
$stmt = $pdo->query("SELECT * FROM seo_settings ORDER BY page_name");
$seoPages = $stmt->fetchAll();

// Edit mode setup
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $editMode = true;
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM seo_settings WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $editData = $stmt->fetch();
}

$csrfToken = generate_csrf_token();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>SEO & Meta Tags</h2>
    <?php if ($editMode): ?>
        <a href="seo_settings.php" class="btn btn-secondary">Cancel Edit</a>
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
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><?php echo $editMode ? 'Edit SEO Settings' : 'Add New Page SEO'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="seo_settings.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    <?php if ($editMode): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                        <input type="hidden" name="existing_og_image" value="<?php echo htmlspecialchars($editData['og_image']); ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Page Name (e.g., 'home', 'rooms', 'contact')</label>
                        <input type="text" class="form-control" name="page_name" value="<?php echo $editMode ? htmlspecialchars($editData['page_name']) : ''; ?>" required>
                    </div>
                    
                    <h6 class="mt-4 border-bottom pb-2">Meta Tags</h6>
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" class="form-control" name="meta_title" value="<?php echo $editMode ? htmlspecialchars($editData['meta_title']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea class="form-control" name="meta_description" rows="3"><?php echo $editMode ? htmlspecialchars($editData['meta_description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <input type="text" class="form-control" name="meta_keywords" value="<?php echo $editMode ? htmlspecialchars($editData['meta_keywords']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Canonical URL</label>
                        <input type="url" class="form-control" name="canonical_url" value="<?php echo $editMode ? htmlspecialchars($editData['canonical_url']) : ''; ?>">
                    </div>
                    
                    <h6 class="mt-4 border-bottom pb-2">Open Graph (Facebook/Twitter)</h6>
                    <div class="form-group">
                        <label>OG Title</label>
                        <input type="text" class="form-control" name="og_title" value="<?php echo $editMode ? htmlspecialchars($editData['og_title']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>OG Description</label>
                        <textarea class="form-control" name="og_description" rows="2"><?php echo $editMode ? htmlspecialchars($editData['og_description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>OG Image</label>
                        <?php if ($editMode && $editData['og_image']): ?>
                            <div class="mb-2">
                                <img src="../<?php echo htmlspecialchars($editData['og_image']); ?>" height="60" alt="Current OG Image">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control-file" name="og_image" accept=".jpg,.jpeg,.png,.webp">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block mt-4"><?php echo $editMode ? 'Update SEO' : 'Add SEO'; ?></button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Table Col -->
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Page</th>
                                <th>Title / Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($seoPages) > 0): ?>
                                <?php foreach ($seoPages as $page): ?>
                                <tr>
                                    <td><span class="badge badge-info"><?php echo htmlspecialchars($page['page_name']); ?></span></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($page['meta_title']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars(substr($page['meta_description'], 0, 50)); ?>...</small>
                                    </td>
                                    <td>
                                        <a href="seo_settings.php?edit=<?php echo $page['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                        <a href="seo_settings.php?action=delete&id=<?php echo $page['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete these SEO settings?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center py-4">No SEO settings found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
