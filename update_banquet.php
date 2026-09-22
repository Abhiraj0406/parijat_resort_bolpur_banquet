<?php
require 'includes/config.php';
$pdo->exec('UPDATE banquet_details SET image_path = NULL');
// $pdo->exec('TRUNCATE TABLE banquet_images');
echo "Banquet DB updated successfully.\n";
