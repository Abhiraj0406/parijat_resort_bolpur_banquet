<?php
$page_title = "Edit Reservation - Parijat Resort Bolpur";
include_once '../includes/header.php';
require_once __DIR__.'/../includes/config.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo '<div class="container my-5"><div class="alert alert-danger" role="alert">Invalid reservation ID.</div></div>';
    include_once '../includes/footer.php';
    exit;
}

$reservationId = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $name = trim($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = trim($_POST['phone']);
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $room_type = $_POST['room_type'];
    $message = trim($_POST['message']);

    $stmt = $pdo->prepare('UPDATE reservations SET name = :name, email = :email, phone = :phone, check_in = :check_in, check_out = :check_out, room_type = :room_type, message = :message WHERE id = :id');
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':check_in' => $check_in,
        ':check_out' => $check_out,
        ':room_type' => $room_type,
        ':message' => $message,
        ':id' => $reservationId
    ]);

    header('Location: bookings.php?msg=updated');
    exit;
}

// Fetch current reservation data
$stmt = $pdo->prepare('SELECT name, email, phone, check_in, check_out, room_type, message FROM reservations WHERE id = :id');
$stmt->execute([':id' => $reservationId]);
$reservation = $stmt->fetch();

if (!$reservation) {
    echo '<div class="container my-5"><div class="alert alert-warning" role="alert">Reservation not found.</div></div>';
    include_once '../includes/footer.php';
    exit;
}
?>
<div class="container my-5">
    <h2 class="mb-4">Edit Reservation</h2>
    <form method="post" class="needs-validation" novalidate>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($reservation['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($reservation['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($reservation['phone']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Check‑in</label>
            <input type="date" name="check_in" class="form-control" value="<?php echo $reservation['check_in']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Check‑out</label>
            <input type="date" name="check_out" class="form-control" value="<?php echo $reservation['check_out']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Room Type</label>
            <select name="room_type" class="form-select" required>
                <option value="Deluxe" <?php if($reservation['room_type']=='Deluxe') echo 'selected'; ?>>Deluxe</option>
                <option value="Standard" <?php if($reservation['room_type']=='Standard') echo 'selected'; ?>>Standard</option>
                <option value="Family" <?php if($reservation['room_type']=='Family') echo 'selected'; ?>>Family</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Special Requests</label>
            <textarea name="message" class="form-control" rows="4"><?php echo htmlspecialchars($reservation['message']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Reservation</button>
        <a href="bookings.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
<?php include_once '../includes/footer.php'; ?>
