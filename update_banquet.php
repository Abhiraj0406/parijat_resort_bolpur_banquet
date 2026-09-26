<?php
require 'includes/config.php';
$pdo->exec("UPDATE banquet_details SET name = 'Parijat AC Banquet & Conference Hall'");
echo "Banquet DB updated to Parijat AC Banquet & Conference Hall successfully.\n";
