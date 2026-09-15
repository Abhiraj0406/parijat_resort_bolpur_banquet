<?php
require_once 'includes/config.php';

echo "--- SEEDING INITIAL CORE DATA (WITHOUT INVENTING TARIFFS OR FAKE NUMBERS) ---\n";

// 1. Seed Initial Amenities (Resort feature list)
$amenities = [
    ['Swimming Pool', 'Crystal clear outdoor swimming pool open daily 10:00 AM – 9:30 PM.', 'fa-tint'],
    ['Labanya\'s Heshel Ghar', 'Freshly cooked authentic Bengali, Chinese, and Mughlai culinary delights.', 'fa-cutlery'],
    ['Private Balconies', 'Every room includes a private balcony overlooking serene nature and lush landscape.', 'fa-sun-o'],
    ['Banquet & Events', 'Ideal for family celebrations, weddings, corporate seminars, and executive meetings.', 'fa-building'],
    ['24/7 Power Backup', 'Continuous power backup ensuring uninterrupted air conditioning and lighting.', 'fa-bolt'],
    ['Free Wi-Fi & Parking', 'Complimentary guest Wi-Fi across premises and secure on-site parking.', 'fa-wifi']
];

$stmt = $pdo->prepare("INSERT INTO amenities (name, description, icon_class, status) VALUES (:n, :d, :i, 'active')");
foreach ($amenities as $am) {
    $chk = $pdo->prepare("SELECT COUNT(*) FROM amenities WHERE name = :n");
    $chk->execute([':n' => $am[0]]);
    if ($chk->fetchColumn() == 0) {
        $stmt->execute([':n' => $am[0], ':d' => $am[1], ':i' => $am[2]]);
        echo "Added Amenity: {$am[0]}\n";
    }
}

// 2. Seed Initial Gallery Images using local template imagery
$galleryItems = [
    ['image/gallery/01.jpg', 'Resort', 'Serene Greenery & Grounds'],
    ['image/gallery/02.jpg', 'Events', 'Banquet & Reception Area'],
    ['image/gallery/03.jpg', 'Dining', 'Labanya\'s Heshel Ghar Dining Hall'],
    ['image/gallery/04.jpg', 'Rooms', 'Deluxe Cottage Exterior View'],
    ['image/gallery/05.jpg', 'Pool', 'Swimming Pool Relaxation Deck'],
    ['image/gallery/06.jpg', 'Nature', 'Morning Light at Bolpur Grounds']
];

$gStmt = $pdo->prepare("INSERT INTO gallery (image_path, category, caption, sort_order) VALUES (:p, :c, :cap, :s)");
foreach ($galleryItems as $idx => $g) {
    $chk = $pdo->prepare("SELECT COUNT(*) FROM gallery WHERE image_path = :p");
    $chk->execute([':p' => $g[0]]);
    if ($chk->fetchColumn() == 0) {
        $gStmt->execute([':p' => $g[0], ':c' => $g[1], ':cap' => $g[2], ':s' => $idx + 1]);
        echo "Added Gallery: {$g[2]}\n";
    }
}

// 3. Seed Days in restaurant_timings
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$tStmt = $pdo->prepare("INSERT INTO restaurant_timings (day_of_week, open_time, close_time, status) VALUES (:d, '09:00:00', '22:30:00', 'active') ON DUPLICATE KEY UPDATE status='active'");
foreach ($days as $day) {
    $tStmt->execute([':d' => $day]);
}
echo "Seeded 7 Days for restaurant_timings.\n";

echo "All initial data synced successfully!\n";
