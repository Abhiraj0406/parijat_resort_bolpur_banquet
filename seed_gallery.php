<?php
require_once 'includes/config.php';

$gallery_items = [
    // Resort & Ambience
    ['image/entrance_slide_1.jpeg', 'Grand Resort Entrance & Welcoming Gate', 'Resort', 1],
    ['image/entrance_slide_2.jpeg', 'Resort Entrance Pathway & Greenery', 'Resort', 2],
    ['image/entrance_slide_3.jpeg', 'Lush Green Front Lawn & Walkway', 'Resort', 3],
    ['image/entrance_slide_4.jpeg', 'Serene Resort Compound & Gardens', 'Resort', 4],
    ['image/cottage_outside_courtyard.jpeg', 'Outside Cottage View — Courtyard Lawn & Gardens', 'Resort', 5],
    ['image/cottage_outside_lawn_garden.jpeg', 'Outside Cottage View — Front Lawn & Cottage Exterior', 'Resort', 6],
    ['image/cottage_exterior_lawn_1.jpeg', 'Outside Cottage View — Panoramic Garden & Cottage Facade', 'Resort', 7],

    // Premium Building
    ['image/premium_building_1.jpeg', 'Premium Accommodation Building — Morning View', 'Premium Building', 10],
    ['image/premium_building_2.jpeg', 'Balconies & Landscaped Garden Facade', 'Premium Building', 11],
    ['image/premium_building_3.jpeg', 'Serene Greenery & Scenic Grounds', 'Premium Building', 12],
    ['image/premium_building_4.jpeg', 'Illuminated Evening Facade', 'Premium Building', 13],
    ['image/premium_building_5.jpeg', 'Premium Building Night Ambience & Lighting', 'Premium Building', 14],
    ['image/premium_building_6.jpeg', 'Peaceful & Secure Night Atmosphere', 'Premium Building', 15],

    // Rooms & Suites (Cottages & Premium Suites)
    ['image/cottage_outside_lawn_garden.jpeg', 'Outside Cottage View — Front Lawn & Cottage Exterior', 'Rooms', 20],
    ['image/cottage_outside_courtyard.jpeg', 'Outside Cottage View — Courtyard Lawn & Gardens', 'Rooms', 21],
    ['image/cottage_outside_raktakarabi.jpeg', 'Outside Cottage View — Raktakarabi Cottage Porch', 'Rooms', 22],
    ['image/cottage_outside_doors.jpeg', 'Outside Cottage View — Veranda & Suite Entrances', 'Rooms', 23],
    ['image/cottage_outside_night.jpeg', 'Outside Cottage View — Night Illumination & Garden Ambiance', 'Rooms', 24],
    ['image/cottage_veranda_2.jpeg', 'Outside Cottage View — Veranda Corridor & Folk Art Murals', 'Rooms', 25],
    ['image/cottage_slide1.jpg', 'Deluxe Cottage Room (Queen Bed Setup)', 'Rooms', 26],
    ['image/cottage_slide2.jpg', 'Deluxe Cottage Sit-out & Side View', 'Rooms', 27],
    ['image/cottage_slide3.jpg', 'Deluxe Cottage Modern Bathroom', 'Rooms', 28],
    ['image/cottage_night_1.jpeg', 'Deluxe Cottage (Illuminated Night View)', 'Rooms', 29],
    ['image/cottage_night_2.jpeg', 'Deluxe Cottage Pathway & Night Lighting', 'Rooms', 30],
    ['image/cottage_night_3.jpeg', 'Deluxe Cottage Serene Evening Ambience', 'Rooms', 31],
    ['image/cottage_night_4.jpeg', 'Deluxe Cottage Landscaped Garden Night View', 'Rooms', 32],
    ['image/ground_slide_1.jpeg', 'Deluxe Premium Ground Floor Suite', 'Rooms', 33],
    ['image/ground_slide_2.jpeg', 'Ground Floor Premium Bed & Interior', 'Rooms', 34],
    ['image/ground_slide_3.jpeg', 'Ground Floor Balcony with Garden View', 'Rooms', 35],
    ['image/ground_slide_4.jpeg', 'Modern Bathroom with Geyser & Toiletries', 'Rooms', 36],
    ['image/ground_slide_5.jpeg', 'Deluxe Room Seating & Lighting', 'Rooms', 37],
    ['image/first_slide_1.jpeg', 'Deluxe Premium First Floor Suite', 'Rooms', 38],
    ['image/first_slide_2.jpeg', 'First Floor Private Balcony & Pool View', 'Rooms', 39],
    ['image/first_slide_3.jpeg', 'First Floor Suite Interior & LED TV', 'Rooms', 40],
    ['image/family_slide1.jpg', 'Family Premium Four Sharing Suite', 'Rooms', 41],
    ['image/family_slide2.jpg', 'Family Suite Dual Double Bed Layout', 'Rooms', 42],
    ['image/family_slide3.jpg', 'Family Suite Wardrobe & Amenities', 'Rooms', 43],
    ['image/family_slide4.jpg', 'Family Suite Modern Washroom', 'Rooms', 44],

    // Swimming Pool & Gardens
    ['image/pool_slide_1.jpeg', 'Crystal Clear Swimming Pool', 'Pool', 45],
    ['image/pool_slide_2.jpeg', 'Poolside Sun Loungers & Relaxation Deck', 'Pool', 41],
    ['image/pool_temp_1.jpeg', 'Refreshing Swimming Pool Oasis & Gardens', 'Pool', 42],
    ['image/pool_temp_2.jpeg', 'Sunny Pool Deck & Landscaped Grounds', 'Pool', 43],
    ['image/pool_temp_3.jpeg', 'Crystal Clear Swimming Pool Area', 'Pool', 44],
    ['image/pool_slide_3.jpeg', 'Scenic Pool Deck & Surrounding Gardens', 'Pool', 45],
    ['image/pool_slide_4.jpeg', 'Poolside Shaded Seating Area', 'Pool', 46],
    ['image/pool_slide_5.jpeg', 'Lush Green Poolside Grounds', 'Pool', 47],
    ['image/pool_slide_6.jpeg', 'Poolside Palm Trees & Pathway', 'Pool', 48],
    ['image/pool_slide_7.jpeg', 'Evening Pool Reflections', 'Pool', 49],
    ['image/pool_slide_8.jpeg', 'Daytime Pool Oasis', 'Pool', 50],
    ['image/pool_slide_9.jpeg', 'Pool & Garden Landscape', 'Pool', 51],
    ['image/pool_slide_10.jpeg', 'Pool Deck Perspective', 'Pool', 52],
    ['image/pool_slide_12.jpeg', 'Swimming Pool Panoramic View', 'Pool', 53],
    ['image/pool_bg.jpg', 'Night Pool Illumination & Lights', 'Pool', 54],

    // Banquet & Events
    ['image/banquet_slide_1.jpeg', 'Parijat AC Banquet & Conference Hall Front View', 'Banquet', 60],
    ['image/banquet_slide_2.jpeg', 'AC Conference & Banquet Setup', 'Banquet', 61],
    ['image/banquet_slide_3.jpeg', 'Banquet Seating Arrangement & Chairs', 'Banquet', 62],
    ['image/banquet_slide_4.jpeg', 'Spacious Event Stage & Hall', 'Banquet', 63],
    ['image/banquet_slide_5.jpeg', 'Banquet Lighting & Festive Decor', 'Banquet', 64],
    ['image/banquet_slide_6.jpeg', 'Conference Hall Table & Seminar Setup', 'Banquet', 65],
    ['image/banquet_slide_7.jpeg', 'Event Reception & Hall Interior', 'Banquet', 66],
    ['image/banquet_slide_8.jpeg', 'Grand Celebration Hall Space', 'Banquet', 67],
    ['image/banquet_slide_9.jpeg', 'Banquet Hall Dining & Buffet Area', 'Banquet', 68],
    ['image/banquet_slide_10.jpeg', 'Fully AC Conference Facilities', 'Banquet', 69],
    ['image/banquet_slide_11.jpeg', 'Premium Banquet Hall Atmosphere', 'Banquet', 70],

    // Dining & Heshel Ghar
    ['image/dining_out.jpg', 'Labanya\'s Heshel Ghar Restaurant Exterior', 'Dining', 75],
    ['image/dining_in.jpg', 'Heshel Ghar Indoor Dining Hall', 'Dining', 76],
    ['image/restaurant_slide_1.jpeg', 'Restaurant Exterior & Courtyard Setup', 'Dining', 77],
    ['image/restaurant_slide_2.jpeg', 'Spacious Multi-Cuisine Dining Hall', 'Dining', 78],
    ['image/restaurant_slide_3.jpeg', 'Heshel Ghar Restaurant Interior View', 'Dining', 79],
    ['image/restaurant_slide_4.jpeg', 'Garden & Restaurant View', 'Dining', 80],
    ['image/restaurant_slide_5.jpeg', 'Outdoor & Courtyard Dining', 'Dining', 81],
    ['image/food2.jpg', 'Authentic Bengali Fish Thali Feast', 'Dining', 82],
    ['image/chinese_food.jpg', 'Crispy Wok-Tossed Chilli Chicken', 'Dining', 83],
    ['image/roll_kabab.jpg', 'Kolkata Kathi Rolls & Sizzling Kebabs', 'Dining', 84],

    // Reception & Lobby
    ['image/reception_desk_1.jpeg', 'Resort Front Office & Reception Desk', 'Reception', 90],
    ['image/reception_lobby_1.jpeg', 'Reception Lounge with Ganesha Artwork', 'Reception', 91],
    ['image/reception_lobby_2.jpeg', 'Guest Waiting Lobby & Luxury Seating', 'Reception', 92],
    ['image/reception_desk_2.jpeg', 'Reception Counter & Chandelier Lighting', 'Reception', 93],
    ['image/reception_new_1.jpeg', 'Grand Resort Reception & Welcome Area', 'Reception', 94],
    ['image/reception_new_2.jpeg', 'Elegant Reception Hall & Lobby', 'Reception', 95],
    ['image/reception_new_3.jpeg', 'Premium Lobby Interiors & Ambience', 'Reception', 96]
];

// Clear existing items and insert comprehensive new gallery items
$pdo->exec("TRUNCATE TABLE gallery");

$stmt = $pdo->prepare("INSERT INTO gallery (image_path, caption, category, sort_order, status) VALUES (?, ?, ?, ?, 'active')");

foreach ($gallery_items as $item) {
    $stmt->execute($item);
}

echo "SUCCESS: Inserted " . count($gallery_items) . " gallery items into database.";
