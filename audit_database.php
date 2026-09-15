<?php
require_once 'includes/config.php';

$tables = [
    'room_categories', 
    'room_rates', 
    'room_images', 
    'amenities', 
    'restaurant_items', 
    'restaurant_timings', 
    'banquet_details', 
    'gallery', 
    'site_settings', 
    'seo_settings', 
    'policies', 
    'enquiries', 
    'admins'
];

echo "=== DATABASE TABLE RECORD AUDIT ===\n";
foreach ($tables as $t) {
    try {
        $c = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
        echo str_pad($t, 22) . ": " . $c . " rows\n";
    } catch (Exception $e) {
        echo str_pad($t, 22) . ": ERROR -> " . $e->getMessage() . "\n";
    }
}
echo "====================================\n";
