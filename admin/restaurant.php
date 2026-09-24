<?php
// admin/restaurant.php
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
            $stmt = $pdo->prepare("DELETE FROM restaurant_items WHERE id = :id");
            if ($stmt->execute([':id' => $id])) {
                $successMsg = "Item deleted successfully.";
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    } elseif ($action === 'toggle_status') {
        try {
            $stmt = $pdo->prepare("UPDATE restaurant_items SET status = IF(status='active', 'inactive', 'active') WHERE id = :id");
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
        $price = (float)($_POST['price'] ?? 0);
        $category = trim($_POST['category'] ?? 'Bengali');
        $is_veg = isset($_POST['is_veg']) ? 1 : 0;

        if (empty($name) || $price <= 0) {
            $errorMsg = 'Item name and a valid price are required.';
        } else {
            try {
                if ($id > 0) {
                    // Update
                    $sql = "UPDATE restaurant_items SET name=:name, description=:desc, price=:price, category=:cat, is_veg=:veg WHERE id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':name'=>$name, ':desc'=>$description, ':price'=>$price, ':cat'=>$category, ':veg'=>$is_veg, ':id'=>$id]);
                    $successMsg = "Menu item updated successfully.";
                } else {
                    // Insert
                    $sql = "INSERT INTO restaurant_items (name, description, price, category, is_veg, status) VALUES (:name, :desc, :price, :cat, :veg, 'active')";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':name'=>$name, ':desc'=>$description, ':price'=>$price, ':cat'=>$category, ':veg'=>$is_veg]);
                    $successMsg = "Menu item added successfully.";
                }
            } catch (PDOException $e) {
                $errorMsg = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Fetch all items
$stmt = $pdo->query("SELECT * FROM restaurant_items ORDER BY category ASC, name ASC");
$items = $stmt->fetchAll();

// Edit mode setup
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $editMode = true;
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM restaurant_items WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $editData = $stmt->fetch();
}

$csrfToken = generate_csrf_token();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Labanya's Heshel Ghar – Menu Items</h2>
        <p class="text-muted mb-0">Manage Bengali, Chinese, and Mughlai culinary selections &amp; pricing</p>
    </div>
    <?php if ($editMode): ?>
        <a href="restaurant.php" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i> Cancel Edit</a>
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
                <h5 class="mb-0 fw-bold"><i class="bi bi-egg-fried me-2 text-primary"></i><?php echo $editMode ? 'Edit Menu Item' : 'Add New Item'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="restaurant.php">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    <?php if ($editMode): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?php echo $editMode ? htmlspecialchars($editData['name']) : ''; ?>" placeholder="e.g. Authentic Bengali Special Thali" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cuisine / Category <span class="text-danger">*</span></label>
                        <select class="form-select" name="category" required>
                            <?php 
                            $cuisines = ['Bengali', 'Chinese', 'Roll, Kabab', 'Beverages / Desserts'];
                            $currCat = $editMode ? $editData['category'] : 'Bengali';
                            foreach ($cuisines as $c): ?>
                                <option value="<?php echo $c; ?>" <?php echo (strcasecmp($currCat, $c) === 0) ? 'selected' : ''; ?>>
                                    <?php echo $c; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Price (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" class="form-control" name="price" value="<?php echo $editMode ? $editData['price'] : ''; ?>" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="vegSwitch" name="is_veg" <?php echo ($editMode && !empty($editData['is_veg'])) ? 'checked' : ''; ?>>
                            <label class="form-check-label fw-semibold" for="vegSwitch">Vegetarian Dish (Veg)</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description / Preparations</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Ingredients, taste notes, or advance preparation note..."><?php echo $editMode ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i> <?php echo $editMode ? 'Update Item' : 'Add to Menu'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Table Col -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-card-list me-2 text-primary"></i>Current Menu Offerings</h5>
                <span class="badge bg-primary"><?php echo count($items); ?> Items</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Cuisine</th>
                                <th>Item &amp; Description</th>
                                <th>Diet</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($items) > 0): ?>
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <?php 
                                        $badgeBg = 'primary';
                                        if (stripos($item['category'], 'Bengali') !== false) $badgeBg = 'warning';
                                        elseif (stripos($item['category'], 'Chinese') !== false) $badgeBg = 'info';
                                        elseif (stripos($item['category'], 'Roll') !== false || stripos($item['category'], 'Kabab') !== false) $badgeBg = 'danger';
                                        ?>
                                        <span class="badge bg-<?php echo $badgeBg; ?>-subtle text-<?php echo $badgeBg; ?> border border-<?php echo $badgeBg; ?>-subtle">
                                            <?php echo htmlspecialchars($item['category']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($item['name']); ?></div>
                                        <small class="text-muted d-block"><?php echo htmlspecialchars($item['description']); ?></small>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['is_veg'])): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-circle-fill" style="font-size:8px;"></i> Veg</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-triangle-fill" style="font-size:8px;"></i> Non-Veg</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong class="text-dark">₹<?php echo number_format($item['price'], 2); ?></strong></td>
                                    <td>
                                        <a href="restaurant.php?action=toggle_status&id=<?php echo $item['id']; ?>" class="badge text-decoration-none bg-<?php echo $item['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($item['status']); ?>
                                        </a>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="restaurant.php?edit=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                        <a href="restaurant.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this item?');" title="Delete"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center py-4">No menu items found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
