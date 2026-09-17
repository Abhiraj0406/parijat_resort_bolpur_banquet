<?php
// admin/create_seed_admin.php
// This script creates an initial admin user so you can log in.
// IMPORTANT: Delete this file after running it once in a production environment.

require_once __DIR__.'/../includes/config.php';

echo "<h2>Seed Admin Creation</h2>";

try {
    // Check if an admin already exists to prevent accidental overwrites
    $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        echo "<p>Admin users already exist. Cannot run seed script.</p>";
        echo "<p>Please delete this file for security.</p>";
        exit;
    }
    
    // Default credentials
    $username = 'admin';
    $email = 'admin@parijatresortbolpur.com';
    $name = 'Super Admin';
    $rawPassword = bin2hex(random_bytes(6)); // Generates a secure random 12-character password
    $passwordHash = password_hash($rawPassword, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO admins (username, email, password_hash, name, role, status) VALUES (:username, :email, :password_hash, :name, 'super', 'active')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password_hash' => $passwordHash,
        ':name' => $name
    ]);
    
    echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb; max-width: 500px;'>";
    echo "<h3>Success!</h3>";
    echo "<p>Initial admin user created successfully.</p>";
    echo "<ul>";
    echo "<li><strong>Username:</strong> " . htmlspecialchars($username) . "</li>";
    echo "<li><strong>Password:</strong> " . htmlspecialchars($rawPassword) . "</li>";
    echo "</ul>";
    echo "<p><strong>IMPORTANT:</strong> Save this password immediately. It is hashed in the database and cannot be recovered.</p>";
    echo "<p><a href='login.php'>Go to Login Page</a></p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<p>Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
