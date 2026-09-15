<?php
require_once 'includes/config.php';

$management_settings = [
    'proprietor_name' => 'Mr. Ananda Kumar Saha',
    'proprietor_role' => 'Proprietor',
    'finance_head_name' => 'Mr. Arindam Saha',
    'finance_head_role' => 'Finance & Administrative Head',
    'manager_name' => 'Mr. Tonmay Guha',
    'manager_role' => 'Resort Manager',
    'manager_phone' => '+91 87775 13554',
    'phone_primary' => '+91 98302 76364',
    'phone_secondary' => '+91 98309 09875',
    'whatsapp_number' => '+91 98309 09875',
    'resort_name' => 'Parijat Resort Bolpur',
    'address' => 'Near Shantiniketan, Bolpur, Birbhum, West Bengal - 731204, India',
    'email_address' => 'info@parijatresortbolpur.com'
];

$stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v2");
foreach ($management_settings as $k => $v) {
    $stmt->execute([':k' => $k, ':v' => $v, ':v2' => $v]);
    echo "Updated: $k => $v\n";
}
echo "Site settings updated successfully.\n";
