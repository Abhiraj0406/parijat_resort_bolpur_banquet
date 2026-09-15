<?php
// admin/profile.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Invalid CSRF token. Please try again.';
    } else {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $errorMsg = 'All fields are required.';
        } elseif ($newPassword !== $confirmPassword) {
            $errorMsg = 'New password and confirmation do not match.';
        } elseif (strlen($newPassword) < 8) {
            $errorMsg = 'New password must be at least 8 characters long.';
        } else {
            // Verify current password
            $adminId = $_SESSION['admin_id'];
            $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = :id");
            $stmt->execute([':id' => $adminId]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($currentPassword, $admin['password_hash'])) {
                // Hash new password
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                
                // Update DB
                $updateStmt = $pdo->prepare("UPDATE admins SET password_hash = :hash WHERE id = :id");
                if ($updateStmt->execute([':hash' => $newHash, ':id' => $adminId])) {
                    $successMsg = 'Password updated successfully.';
                } else {
                    $errorMsg = 'Failed to update password. Database error.';
                }
            } else {
                $errorMsg = 'Incorrect current password.';
            }
        }
    }
}

$csrfToken = generate_csrf_token();
?>

<div class="row justify-content-center">
    <div class="col-md-6 mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">Change Password</h4>
            </div>
            <div class="card-body">
                <?php if ($errorMsg): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
                <?php endif; ?>
                <?php if ($successMsg): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                        <small class="form-text text-muted">Must be at least 8 characters long.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-3">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
