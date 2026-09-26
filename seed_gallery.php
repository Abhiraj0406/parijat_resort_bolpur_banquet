<?php
require_once 'includes/config.php';

$gallery_items = [
    // Resort & Ambience
    ['image/entrance_slide_1.jpeg', 'Grand Resort Entrance & Welcoming Gate', 'Resort', 1],
    ['image/entrance_slide_2.jpeg', 'Resort Entrance Pathway & Greenery', 'Resort', 2],
    ['image/entrance_slide_3.jpeg', 'Lush Green Front Lawn & Walkway', 'Resort', 3],
    ['image/entrance_slide_4.jpeg', 'Serene Resort Compound & Gardens', 'Resort', 4],
    ['image/premium_building_1.jpeg', 'Premium Accommodation Building (Morning View)', 'Resort', 5],
    ['image/premium_building_2.jpeg', 'Balconies & Landscaped Garden Facade', 'Resort', 6],
    ['image/premium_building_3.jpeg', 'Serene Greenery & Scenic Grounds', 'Resort', 7],
    ['image/premium_building_4.jpeg', 'Illuminated Evening Facade', 'Resort', 8],
    ['image/premium_building_5.jpeg', 'Premium Building (Night Ambience & Lighting)', 'Resort', 9],
    ['image/premium_building_6.jpeg', 'Peaceful & Secure Night Atmosphere', 'Resort', 10],
    ['image/about_bg.jpg', 'Tranquil Resort Surroundings in Bolpur', 'Resort', 11],

    // Rooms & Suites
    ['image/cottage_slide1.jpg', 'Deluxe Cottage Room (King Bed Setup)', 'Rooms', 12],
    ['image/cottage_slide2.jpg', 'Deluxe Cottage Sit-out & Side View', 'Rooms', 13],
    ['image/cottage_slide3.jpg', 'Deluxe Cottage Modern Bathroom', 'Rooms', 14],
    ['image/ground_slide_1.jpeg', 'Deluxe Premium Ground Floor Suite', 'Rooms', 15],
    ['image/ground_slide_2.jpeg', 'Ground Floor Premium Bed & Interior', 'Rooms', 16],
    ['image/ground_slide_3.jpeg', 'Ground Floor Balcony with Garden View', 'Rooms', 17],
    ['image/ground_slide_4.jpeg', 'Modern Bathroom with Geyser & Toiletries', 'Rooms', 18],
    ['image/ground_slide_5.jpeg', 'Deluxe Room Seating & Lighting', 'Rooms', 19],
    ['image/first_slide_1.jpeg', 'Deluxe Premium First Floor Suite', 'Rooms', 20],
    ['image/first_slide_2.jpeg', 'First Floor Private Balcony & Pool View', 'Rooms', 21],
    ['image/first_slide_3.jpeg', 'First Floor Suite Interior & LED TV', 'Rooms', 22],
    ['image/family_slide1.jpg', 'Family Premium Four Sharing Suite', 'Rooms', 23],
    ['image/family_slide2.jpg', 'Family Suite Dual Double Bed Layout', 'Rooms', 24],
    ['image/family_slide3.jpg', 'Family Suite Wardrobe & Amenities', 'Rooms', 25],
    ['image/family_slide4.jpg', 'Family Suite Modern Washroom', 'Rooms', 26],

    // Swimming Pool & Gardens
    ['image/pool_slide_1.jpeg', 'Crystal Clear Swimming Pool', 'Pool', 27],
    ['image/pool_slide_2.jpeg', 'Poolside Sun Loungers & Relaxation Deck', 'Pool', 28],
    ['image/pool_slide_3.jpeg', 'Scenic Pool Deck & Surrounding Gardens', 'Pool', 29],
    ['image/pool_slide_4.jpeg', 'Poolside Shaded Seating Area', 'Pool', 30],
    ['image/pool_slide_5.jpeg', 'Lush Green Poolside Grounds', 'Pool', 31],
    ['image/pool_slide_6.jpeg', 'Poolside Palm Trees & Pathway', 'Pool', 32],
    ['image/pool_slide_7.jpeg', 'Evening Pool Reflections', 'Pool', 33],
    ['image/pool_slide_8.jpeg', 'Daytime Pool Oasis', 'Pool', 34],
    ['image/pool_slide_9.jpeg', 'Pool & Garden Landscape', 'Pool', 35],
    ['image/pool_slide_10.jpeg', 'Pool Deck Perspective', 'Pool', 36],
    ['image/pool_slide_12.jpeg', 'Swimming Pool Panoramic View', 'Pool', 37],
    ['image/pool_bg.jpg', 'Night Pool Illumination & Lights', 'Pool', 38],

    // Banquet & Events
    ['image/banquet_slide_1.jpeg', 'Parijat AC Banquet & Conference Hall Front View', 'Banquet', 39],
    ['image/banquet_slide_2.jpeg', 'AC Conference & Banquet Setup', 'Banquet', 40],
    ['image/banquet_slide_3.jpeg', 'Banquet Seating Arrangement & Chairs', 'Banquet', 41],
    ['image/banquet_slide_4.jpeg', 'Spacious Event Stage & Hall', 'Banquet', 42],
    ['image/banquet_slide_5.jpeg', 'Banquet Lighting & Festive Decor', 'Banquet', 43],
    ['image/banquet_slide_6.jpeg', 'Conference Hall Table & Seminar Setup', 'Banquet', 44],
    ['image/banquet_slide_7.jpeg', 'Event Reception & Hall Interior', 'Banquet', 45],
    ['image/banquet_slide_8.jpeg', 'Grand Celebration Hall Space', 'Banquet', 46],
    ['image/banquet_slide_9.jpeg', 'Banquet Hall Dining & Buffet Area', 'Banquet', 47],
    ['image/banquet_slide_10.jpeg', 'Fully AC Conference Facilities', 'Banquet', 48],
    ['image/banquet_slide_11.jpeg', 'Premium Banquet Hall Atmosphere', 'Banquet', 49],

    // Dining & Heshel Ghar
    ['image/dining_out.jpg', 'Labanya\'s Heshel Ghar Restaurant Exterior', 'Dining', 50],
    ['image/dining_in.jpg', 'Heshel Ghar Indoor Dining Hall', 'Dining', 51],
    ['image/restaurant_slide_1.jpeg', 'Restaurant Exterior & Courtyard Setup', 'Dining', 52],
    ['image/restaurant_slide_2.jpeg', 'Spacious Multi-Cuisine Dining Hall', 'Dining', 53],
    ['image/restaurant_slide_3.jpeg', 'Heshel Ghar Restaurant Interior View', 'Dining', 54],
    ['image/restaurant_slide_4.jpeg', 'Garden & Restaurant View', 'Dining', 55],
    ['image/restaurant_slide_5.jpeg', 'Outdoor & Courtyard Dining', 'Dining', 56],
    ['image/food2.jpg', 'Authentic Bengali Fish Thali Feast', 'Dining', 57],
    ['image/chinese_food.jpg', 'Crispy Wok-Tossed Chilli Chicken', 'Dining', 58],
    ['image/roll_kabab.jpg', 'Kolkata Kathi Rolls & Sizzling Kebabs', 'Dining', 59],

    // Reception & Lobby
    ['image/reception1.jpg', 'Resort Front Office & Reception Desk', 'Reception', 60],
    ['image/reception2.jpg', 'Welcome Lobby & Guest Lounge', 'Reception', 61]
];

// Clear existing items and insert comprehensive new gallery items
$pdo->exec("TRUNCATE TABLE gallery");

$stmt = $pdo->prepare("INSERT INTO gallery (image_path, caption, category, sort_order, status) VALUES (?, ?, ?, ?, 'active')");

foreach ($gallery_items as $item) {
    $stmt->execute($item);
}

echo "SUCCESS: Inserted " . count($gallery_items) . " gallery items into database.";
