<?php
// admin/restaurant_timings.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';

// Handle Form Submission (Update Timings)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Invalid CSRF token.';
    } else {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        try {
            $pdo->beginTransaction();
            foreach ($days as $day) {
                $open = $_POST['open'][$day] ?? '08:00:00';
                $close = $_POST['close'][$day] ?? '22:00:00';
                $status = isset($_POST['status'][$day]) ? 'active' : 'inactive';
                
                // Insert or Update using DUPLICATE KEY
                $sql = "INSERT INTO restaurant_timings (day_of_week, open_time, close_time, status) 
                        VALUES (:day, :open, :close, :status)
                        ON DUPLICATE KEY UPDATE open_time=:open2, close_time=:close2, status=:status2";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':day' => $day,
                    ':open' => $open, ':close' => $close, ':status' => $status,
                    ':open2' => $open, ':close2' => $close, ':status2' => $status
                ]);
            }
            $pdo->commit();
            $successMsg = "Restaurant timings updated successfully.";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch current timings
$stmt = $pdo->query("SELECT * FROM restaurant_timings");
$rawTimings = $stmt->fetchAll();

// Map by day
$timings = [];
foreach ($rawTimings as $t) {
    $timings[$t['day_of_week']] = $t;
}

$daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$csrfToken = generate_csrf_token();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Restaurant Timings</h2>
</div>

<?php if ($errorMsg): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
<?php endif; ?>
<?php if ($successMsg): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="restaurant_timings.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Day</th>
                            <th>Is Open?</th>
                            <th>Opening Time</th>
                            <th>Closing Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daysOfWeek as $day): 
                            $open = $timings[$day]['open_time'] ?? '08:00:00';
                            $close = $timings[$day]['close_time'] ?? '22:00:00';
                            $status = $timings[$day]['status'] ?? 'active';
                        ?>
                        <tr>
                            <td class="align-middle"><strong><?php echo $day; ?></strong></td>
                            <td class="align-middle text-center">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="status_<?php echo $day; ?>" name="status[<?php echo $day; ?>]" <?php echo $status === 'active' ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="status_<?php echo $day; ?>"></label>
                                </div>
                            </td>
                            <td>
                                <input type="time" class="form-control" name="open[<?php echo $day; ?>]" value="<?php echo date('H:i', strtotime($open)); ?>">
                            </td>
                            <td>
                                <input type="time" class="form-control" name="close[<?php echo $day; ?>]" value="<?php echo date('H:i', strtotime($close)); ?>">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Save Timings</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
