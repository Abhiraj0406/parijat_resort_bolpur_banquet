<?php
require 'includes/config.php';
$pdo->exec('TRUNCATE TABLE gallery');
echo "Gallery table truncated successfully.\n";
