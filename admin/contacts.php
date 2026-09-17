<?php
// admin/contacts.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';

// Handle Delete/Status Update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM contact_inquiries WHERE id = :id");
            if ($stmt->execute([':id' => $id])) {
                $successMsg = "Message deleted successfully.";
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    } elseif ($action === 'mark_read') {
        try {
            $stmt = $pdo->prepare("UPDATE contact_inquiries SET status = 'read' WHERE id = :id");
            if ($stmt->execute([':id' => $id])) {
                $successMsg = "Message marked as read.";
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    } elseif ($action === 'mark_closed') {
        try {
            $stmt = $pdo->prepare("UPDATE contact_inquiries SET status = 'closed' WHERE id = :id");
            if ($stmt->execute([':id' => $id])) {
                $successMsg = "Message marked as closed.";
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch all messages
$stmt = $pdo->query("SELECT * FROM contact_inquiries ORDER BY created_at DESC");
$messages = $stmt->fetchAll();

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Contact Enquiries</h2>
        <p class="text-muted mb-0">Manage messages from the website contact form</p>
    </div>
</div>

<?php if ($errorMsg): ?>
    <div class="alert alert-danger shadow-sm border-0"><i class="bi bi-exclamation-circle me-2"></i><?php echo htmlspecialchars($errorMsg); ?></div>
<?php endif; ?>
<?php if ($successMsg): ?>
    <div class="alert alert-success shadow-sm border-0"><i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($successMsg); ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-envelope-open me-2 text-primary"></i>Inbox</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Date</th>
                        <th>Sender</th>
                        <th>Subject / Message</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($messages) > 0): ?>
                        <?php foreach ($messages as $msg): ?>
                        <tr class="<?php echo $msg['status'] === 'new' ? 'table-warning bg-opacity-25' : ''; ?>">
                            <td class="text-nowrap ps-3">
                                <div class="fw-semibold"><?php echo date('d M Y', strtotime($msg['created_at'])); ?></div>
                                <div class="text-muted small"><?php echo date('h:i A', strtotime($msg['created_at'])); ?></div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                        <?php echo strtoupper(substr($msg['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($msg['name']); ?></div>
                                        <div class="small text-muted"><i class="bi bi-envelope me-1"></i><a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" class="text-decoration-none text-muted"><?php echo htmlspecialchars($msg['email']); ?></a></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($msg['subject']); ?></div>
                                <div class="text-muted small" style="max-width: 300px; white-space: normal;">
                                    <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($msg['status'] === 'new'): ?>
                                    <span class="badge bg-danger rounded-pill px-3 py-2">NEW</span>
                                <?php elseif ($msg['status'] === 'read'): ?>
                                    <span class="badge bg-info text-dark rounded-pill px-3 py-2">READ</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">CLOSED</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-nowrap text-end pe-3">
                                <?php if ($msg['status'] === 'new'): ?>
                                    <a href="contacts.php?action=mark_read&id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-outline-primary" title="Mark Read"><i class="bi bi-eye"></i> Read</a>
                                <?php endif; ?>
                                <?php if ($msg['status'] !== 'closed'): ?>
                                    <a href="contacts.php?action=mark_closed&id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-outline-secondary" title="Mark Closed"><i class="bi bi-check-circle"></i> Close</a>
                                <?php endif; ?>
                                <a href="contacts.php?action=delete&id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this message permanently?');" title="Delete"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-envelope-paper" style="font-size: 2rem;"></i><br>
                                No contact messages found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
