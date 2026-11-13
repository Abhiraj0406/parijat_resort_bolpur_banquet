<?php
$current_page = "gallery";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Master list of authentic resort photos across all categories
$allDefaultGallery = [
    // Resort & Architecture
    ['image_path' => 'image/entrance_slide_1.jpeg', 'caption' => 'Grand Resort Entrance & Welcoming Gate', 'category' => 'Resort', 'sort_order' => 1],
    ['image_path' => 'image/entrance_slide_2.jpeg', 'caption' => 'Resort Entrance Pathway & Greenery', 'category' => 'Resort', 'sort_order' => 2],
    ['image_path' => 'image/entrance_slide_3.jpeg', 'caption' => 'Lush Green Front Lawn & Walkway', 'category' => 'Resort', 'sort_order' => 3],
    ['image_path' => 'image/entrance_slide_4.jpeg', 'caption' => 'Serene Resort Compound & Gardens', 'category' => 'Resort', 'sort_order' => 4],
    ['image_path' => 'image/cottage_outside_courtyard.jpeg', 'caption' => 'Outside Cottage View — Courtyard Lawn & Gardens', 'category' => 'Resort', 'sort_order' => 5],
    ['image_path' => 'image/cottage_outside_lawn_garden.jpeg', 'caption' => 'Outside Cottage View — Front Lawn & Cottage Exterior', 'category' => 'Resort', 'sort_order' => 6],
    ['image_path' => 'image/cottage_exterior_lawn_1.jpeg', 'caption' => 'Outside Cottage View — Panoramic Garden & Cottage Facade', 'category' => 'Resort', 'sort_order' => 7],

    // Premium Building
    ['image_path' => 'image/premium_building_1.jpeg', 'caption' => 'Premium Accommodation Building — Morning View', 'category' => 'Premium Building', 'sort_order' => 10],
    ['image_path' => 'image/premium_building_2.jpeg', 'caption' => 'Balconies & Landscaped Garden Facade', 'category' => 'Premium Building', 'sort_order' => 11],
    ['image_path' => 'image/premium_building_3.jpeg', 'caption' => 'Serene Greenery & Scenic Grounds', 'category' => 'Premium Building', 'sort_order' => 12],
    ['image_path' => 'image/premium_building_4.jpeg', 'caption' => 'Illuminated Evening Facade', 'category' => 'Premium Building', 'sort_order' => 13],
    ['image_path' => 'image/premium_building_5.jpeg', 'caption' => 'Premium Building Night Ambience & Lighting', 'category' => 'Premium Building', 'sort_order' => 14],
    ['image_path' => 'image/premium_building_6.jpeg', 'caption' => 'Peaceful & Secure Night Atmosphere', 'category' => 'Premium Building', 'sort_order' => 15],

    // Rooms & Suites (Cottages & Premium Suites)
    ['image_path' => 'image/cottage_outside_lawn_garden.jpeg', 'caption' => 'Outside Cottage View — Front Lawn & Cottage Exterior', 'category' => 'Rooms', 'sort_order' => 20],
    ['image_path' => 'image/cottage_outside_courtyard.jpeg', 'caption' => 'Outside Cottage View — Courtyard Lawn & Gardens', 'category' => 'Rooms', 'sort_order' => 21],
    ['image_path' => 'image/cottage_outside_raktakarabi.jpeg', 'caption' => 'Outside Cottage View — Raktakarabi Cottage Porch', 'category' => 'Rooms', 'sort_order' => 22],
    ['image_path' => 'image/cottage_outside_doors.jpeg', 'caption' => 'Outside Cottage View — Veranda & Suite Entrances', 'category' => 'Rooms', 'sort_order' => 23],
    ['image_path' => 'image/cottage_outside_night.jpeg', 'caption' => 'Outside Cottage View — Night Illumination & Garden Ambiance', 'category' => 'Rooms', 'sort_order' => 24],
    ['image_path' => 'image/cottage_veranda_2.jpeg', 'caption' => 'Outside Cottage View — Veranda Corridor & Folk Art Murals', 'category' => 'Rooms', 'sort_order' => 25],
    ['image_path' => 'image/cottage_slide1.jpg', 'caption' => 'Deluxe Cottage Room (Queen Bed Setup)', 'category' => 'Rooms', 'sort_order' => 26],
    ['image_path' => 'image/cottage_slide2.jpg', 'caption' => 'Deluxe Cottage Sit-out & Side View', 'category' => 'Rooms', 'sort_order' => 27],
    ['image_path' => 'image/cottage_slide3.jpg', 'caption' => 'Deluxe Cottage Modern Bathroom', 'category' => 'Rooms', 'sort_order' => 28],
    ['image_path' => 'image/cottage_night_1.jpeg', 'caption' => 'Deluxe Cottage (Illuminated Night View)', 'category' => 'Rooms', 'sort_order' => 29],
    ['image_path' => 'image/cottage_night_2.jpeg', 'caption' => 'Deluxe Cottage Pathway & Night Lighting', 'category' => 'Rooms', 'sort_order' => 30],
    ['image_path' => 'image/cottage_night_3.jpeg', 'caption' => 'Deluxe Cottage Serene Evening Ambience', 'category' => 'Rooms', 'sort_order' => 31],
    ['image_path' => 'image/cottage_night_4.jpeg', 'caption' => 'Deluxe Cottage Landscaped Garden Night View', 'category' => 'Rooms', 'sort_order' => 32],
    ['image_path' => 'image/ground_slide_1.jpeg', 'caption' => 'Deluxe Premium with Private Balcony - Lawn & Garden View', 'category' => 'Rooms', 'sort_order' => 33],
    ['image_path' => 'image/ground_slide_2.jpeg', 'caption' => 'Ground Floor Premium Bed & Interior', 'category' => 'Rooms', 'sort_order' => 34],
    ['image_path' => 'image/ground_slide_3.jpeg', 'caption' => 'Ground Floor Balcony with Garden View', 'category' => 'Rooms', 'sort_order' => 35],
    ['image_path' => 'image/ground_slide_4.jpeg', 'caption' => 'Modern Bathroom with Geyser & Toiletries', 'category' => 'Rooms', 'sort_order' => 36],
    ['image_path' => 'image/ground_slide_5.jpeg', 'caption' => 'Deluxe Room Seating & Lighting', 'category' => 'Rooms', 'sort_order' => 37],
    ['image_path' => 'image/first_slide_1.jpeg', 'caption' => 'Deluxe Premium with Private Balcony - Pool View with Garden & Lawn', 'category' => 'Rooms', 'sort_order' => 38],
    ['image_path' => 'image/first_slide_2.jpeg', 'caption' => 'First Floor Private Balcony & Pool View', 'category' => 'Rooms', 'sort_order' => 39],
    ['image_path' => 'image/first_slide_3.jpeg', 'caption' => 'First Floor Suite Interior & LED TV', 'category' => 'Rooms', 'sort_order' => 40],
    ['image_path' => 'image/family_slide1.jpg', 'caption' => 'Family Premium Four Sharing Suite', 'category' => 'Rooms', 'sort_order' => 41],
    ['image_path' => 'image/family_slide2.jpg', 'caption' => 'Family Suite Dual Double Bed Layout', 'category' => 'Rooms', 'sort_order' => 42],
    ['image_path' => 'image/family_slide3.jpg', 'caption' => 'Family Suite Wardrobe & Amenities', 'category' => 'Rooms', 'sort_order' => 43],
    ['image_path' => 'image/family_slide4.jpg', 'caption' => 'Family Suite Modern Washroom', 'category' => 'Rooms', 'sort_order' => 44],

    // Swimming Pool & Gardens
    ['image_path' => 'image/pool_slide_1.jpeg', 'caption' => 'Crystal Clear Swimming Pool', 'category' => 'Pool', 'sort_order' => 45],
    ['image_path' => 'image/pool_slide_2.jpeg', 'caption' => 'Poolside Sun Loungers & Relaxation Deck', 'category' => 'Pool', 'sort_order' => 46],
    ['image_path' => 'image/pool_temp_1.jpeg', 'caption' => 'Refreshing Swimming Pool Oasis & Gardens', 'category' => 'Pool', 'sort_order' => 47],
    ['image_path' => 'image/pool_temp_2.jpeg', 'caption' => 'Sunny Pool Deck & Landscaped Grounds', 'category' => 'Pool', 'sort_order' => 48],
    ['image_path' => 'image/pool_temp_3.jpeg', 'caption' => 'Crystal Clear Swimming Pool Area', 'category' => 'Pool', 'sort_order' => 49],
    ['image_path' => 'image/pool_slide_3.jpeg', 'caption' => 'Scenic Pool Deck & Surrounding Gardens', 'category' => 'Pool', 'sort_order' => 50],
    ['image_path' => 'image/pool_slide_4.jpeg', 'caption' => 'Poolside Shaded Seating Area', 'category' => 'Pool', 'sort_order' => 51],
    ['image_path' => 'image/pool_slide_5.jpeg', 'caption' => 'Lush Green Poolside Grounds', 'category' => 'Pool', 'sort_order' => 52],
    ['image_path' => 'image/pool_slide_6.jpeg', 'caption' => 'Poolside Palm Trees & Pathway', 'category' => 'Pool', 'sort_order' => 53],
    ['image_path' => 'image/pool_slide_7.jpeg', 'caption' => 'Evening Pool Reflections', 'category' => 'Pool', 'sort_order' => 54],
    ['image_path' => 'image/pool_slide_8.jpeg', 'caption' => 'Daytime Pool Oasis', 'category' => 'Pool', 'sort_order' => 55],
    ['image_path' => 'image/pool_slide_9.jpeg', 'caption' => 'Pool & Garden Landscape', 'category' => 'Pool', 'sort_order' => 56],
    ['image_path' => 'image/pool_slide_10.jpeg', 'caption' => 'Pool Deck Perspective', 'category' => 'Pool', 'sort_order' => 57],
    ['image_path' => 'image/pool_slide_12.jpeg', 'caption' => 'Swimming Pool Panoramic View', 'category' => 'Pool', 'sort_order' => 58],
    ['image_path' => 'image/pool_bg.jpg', 'caption' => 'Night Pool Illumination & Lights', 'category' => 'Pool', 'sort_order' => 59],

    // Banquet & Events
    ['image_path' => 'image/banquet_slide_1.jpeg', 'caption' => 'Parijat AC Banquet & Conference Hall Front View', 'category' => 'Banquet', 'sort_order' => 60],
    ['image_path' => 'image/banquet_slide_2.jpeg', 'caption' => 'AC Conference & Banquet Setup', 'category' => 'Banquet', 'sort_order' => 61],
    ['image_path' => 'image/banquet_slide_3.jpeg', 'caption' => 'Banquet Seating Arrangement & Chairs', 'category' => 'Banquet', 'sort_order' => 62],
    ['image_path' => 'image/banquet_slide_4.jpeg', 'caption' => 'Spacious Event Stage & Hall', 'category' => 'Banquet', 'sort_order' => 63],
    ['image_path' => 'image/banquet_slide_5.jpeg', 'caption' => 'Banquet Lighting & Festive Decor', 'category' => 'Banquet', 'sort_order' => 64],
    ['image_path' => 'image/banquet_slide_6.jpeg', 'caption' => 'Conference Hall Table & Seminar Setup', 'category' => 'Banquet', 'sort_order' => 65],
    ['image_path' => 'image/banquet_slide_7.jpeg', 'caption' => 'Event Reception & Hall Interior', 'category' => 'Banquet', 'sort_order' => 66],
    ['image_path' => 'image/banquet_slide_8.jpeg', 'caption' => 'Grand Celebration Hall Space', 'category' => 'Banquet', 'sort_order' => 67],
    ['image_path' => 'image/banquet_slide_9.jpeg', 'caption' => 'Banquet Hall Dining & Buffet Area', 'category' => 'Banquet', 'sort_order' => 68],
    ['image_path' => 'image/banquet_slide_10.jpeg', 'caption' => 'Fully AC Conference Facilities', 'category' => 'Banquet', 'sort_order' => 69],
    ['image_path' => 'image/banquet_slide_11.jpeg', 'caption' => 'Premium Banquet Hall Atmosphere', 'category' => 'Banquet', 'sort_order' => 70],

    // Dining & Heshel Ghar
    ['image_path' => 'image/dining_out.jpg', 'caption' => 'Labanya\'s Heshel Ghar Restaurant Exterior', 'category' => 'Dining', 'sort_order' => 75],
    ['image_path' => 'image/dining_in.jpg', 'caption' => 'Heshel Ghar Indoor Dining Hall', 'category' => 'Dining', 'sort_order' => 76],
    ['image_path' => 'image/restaurant_slide_1.jpeg', 'caption' => 'Restaurant Exterior & Courtyard Setup', 'category' => 'Dining', 'sort_order' => 77],
    ['image_path' => 'image/restaurant_slide_2.jpeg', 'caption' => 'Spacious Multi-Cuisine Dining Hall', 'category' => 'Dining', 'sort_order' => 78],
    ['image_path' => 'image/restaurant_slide_3.jpeg', 'caption' => 'Heshel Ghar Restaurant Interior View', 'category' => 'Dining', 'sort_order' => 79],
    ['image_path' => 'image/restaurant_slide_4.jpeg', 'caption' => 'Garden & Restaurant View', 'category' => 'Dining', 'sort_order' => 80],
    ['image_path' => 'image/restaurant_slide_5.jpeg', 'caption' => 'Outdoor & Courtyard Dining', 'category' => 'Dining', 'sort_order' => 81],
    ['image_path' => 'image/food2.jpg', 'caption' => 'Authentic Bengali Fish Thali Feast', 'category' => 'Dining', 'sort_order' => 82],
    ['image_path' => 'image/chinese_food.jpg', 'caption' => 'Crispy Wok-Tossed Chilli Chicken', 'category' => 'Dining', 'sort_order' => 83],
    ['image_path' => 'image/roll_kabab.jpg', 'caption' => 'Kolkata Kathi Rolls & Sizzling Kebabs', 'category' => 'Dining', 'sort_order' => 84],

    // Reception & Lobby
    ['image_path' => 'image/reception_desk_1.jpeg', 'caption' => 'Resort Front Office & Reception Desk', 'category' => 'Reception', 'sort_order' => 90],
    ['image_path' => 'image/reception_lobby_1.jpeg', 'caption' => 'Reception Lounge with Ganesha Artwork', 'category' => 'Reception', 'sort_order' => 91],
    ['image_path' => 'image/reception_lobby_2.jpeg', 'caption' => 'Guest Waiting Lobby & Luxury Seating', 'category' => 'Reception', 'sort_order' => 92],
    ['image_path' => 'image/reception_desk_2.jpeg', 'caption' => 'Reception Counter & Chandelier Lighting', 'category' => 'Reception', 'sort_order' => 93],
    ['image_path' => 'image/reception_new_1.jpeg', 'caption' => 'Grand Resort Reception & Welcome Area', 'category' => 'Reception', 'sort_order' => 94],
    ['image_path' => 'image/reception_new_2.jpeg', 'caption' => 'Elegant Reception Hall & Lobby', 'category' => 'Reception', 'sort_order' => 95],
    ['image_path' => 'image/reception_new_3.jpeg', 'caption' => 'Premium Lobby Interiors & Ambience', 'category' => 'Reception', 'sort_order' => 96]
];

// Self-healing database sync: automatically populate database with authentic photos
try {
    // Permanently remove unwanted stock about_bg.jpg if present in database
    $pdo->exec("DELETE FROM gallery WHERE image_path = 'image/about_bg.jpg'");

    $existingRows = $pdo->query("SELECT image_path, category FROM gallery WHERE image_path != 'image/about_bg.jpg'")->fetchAll(PDO::FETCH_ASSOC);
    $existingSet = [];
    foreach ($existingRows as $row) {
        $existingSet[$row['image_path'] . '|' . $row['category']] = true;
    }

    $insertStmt = $pdo->prepare("INSERT INTO gallery (image_path, caption, category, sort_order, status) VALUES (?, ?, ?, ?, 'active')");
    $updateStmt = $pdo->prepare("UPDATE gallery SET caption = ?, sort_order = ? WHERE image_path = ? AND category = ?");

    foreach ($allDefaultGallery as $item) {
        $key = $item['image_path'] . '|' . $item['category'];
        if (!isset($existingSet[$key])) {
            $insertStmt->execute([$item['image_path'], $item['caption'], $item['category'], $item['sort_order']]);
            $existingSet[$key] = true;
        } else {
            $updateStmt->execute([$item['caption'], $item['sort_order'], $item['image_path'], $item['category']]);
        }
    }
} catch (Exception $e) {}

// Fetch gallery images managed dynamically by admin
$stmt = $pdo->query("SELECT * FROM gallery WHERE status = 'active' AND image_path != 'image/about_bg.jpg' ORDER BY sort_order ASC, id ASC");
$dynamicGallery = $stmt->fetchAll();

// Guarantee all authentic photos are present even if database has partial records
$loadedKeys = [];
foreach ($dynamicGallery as $dg) {
    $loadedKeys[$dg['image_path'] . '|' . ($dg['category'] ?? '')] = true;
}
foreach ($allDefaultGallery as $item) {
    $key = $item['image_path'] . '|' . $item['category'];
    if (!isset($loadedKeys[$key])) {
        $dynamicGallery[] = $item;
        $loadedKeys[$key] = true;
    }
}
?>

<!--================Breadcrumb Area =================-->
<section class="breadcrumb_area">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
    <div class="container">
        <div class="page-cover text-center">
            <h2 class="page-cover-tittle font-weight-bold">Photo Gallery</h2>
            <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li class="active">Gallery</li>
            </ol>
        </div>
    </div>
</section>
<!--================Breadcrumb Area =================-->

<!-- Gallery Page Scoped Styles -->
<style>
    .gallery-filter-btn {
        border: 1px solid #ffc107;
        background: #ffffff;
        color: #333333;
        padding: 8px 18px;
        margin: 4px;
        border-radius: 30px;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .gallery-filter-btn:hover,
    .gallery-filter-btn.active {
        background: #ffc107;
        color: #212529;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.35);
        transform: translateY(-1px);
    }
    .gallery_item .gallery_img {
        background: #000 !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        height: 270px !important;
        position: relative !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: 0 6px 20px rgba(0,0,0,0.12) !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease !important;
    }
    .gallery_item .gallery_img:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.22) !important;
    }
    .gallery_item .gallery_img img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        display: block !important;
        border-radius: 0 !important;
        transition: transform 0.4s ease !important;
    }
    .gallery_item .gallery_img:hover img {
        transform: scale(1.08) !important;
    }
    .gallery_item .gallery_img .hover {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.2) 60%, transparent 100%) !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-end !important;
        padding: 18px !important;
        opacity: 0;
        transition: opacity 0.3s ease !important;
        backdrop-filter: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }
    .gallery_item .gallery_img:hover .hover {
        opacity: 1 !important;
    }
    .gallery_item .gallery_img .hover span,
    .gallery_item .gallery_img .hover a,
    .gallery_item .gallery_img .hover i {
        color: #ffffff !important;
        text-shadow: 1px 1px 4px rgba(0,0,0,0.9) !important;
    }
    .gallery-category-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        background: rgba(0,0,0,0.65);
        color: #ffc107;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        z-index: 2;
        backdrop-filter: blur(4px);
    }
    .gallery_item {
        transition: all 0.35s ease;
    }
</style>

<!--================Gallery Area =================-->
<section class="gallery_area section_gap bg-light">
    <div class="container">
        <div class="section_title text-center mb-4">
            <h2 class="title_color font-weight-bold mt-2">Moments at Parijat Resort</h2>
            <p class="text-muted">Explore authentic photographs of our cottages, swimming pool, dining hall, lawns, and banquet facilities.</p>
        </div>

        <!-- Filter Buttons -->
        <div class="text-center mb-5 d-flex flex-wrap justify-content-center" id="galleryFilterControls">
            <button type="button" class="gallery-filter-btn active" data-filter="all">
                <i class="fa fa-th mr-1"></i> All Photos (<?php echo count($dynamicGallery); ?>)
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="Resort">
                <i class="fa fa-tree mr-1"></i> Resort &amp; Ambience
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="Rooms">
                <i class="fa fa-bed mr-1"></i> Rooms &amp; Suites
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="Pool">
                <i class="fa fa-tint mr-1"></i> Swimming Pool
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="Banquet">
                <i class="fa fa-star mr-1"></i> Banquet &amp; Events
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="Dining">
                <i class="fa fa-cutlery mr-1"></i> Dining &amp; Food
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="Reception">
                <i class="fa fa-info-circle mr-1"></i> Reception &amp; Lobby
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="Premium Building">
                <i class="fa fa-building mr-1"></i> Premium Building
            </button>
        </div>

        <!-- Gallery Grid -->
        <div class="row imageGallery1" id="gallery">
            <?php foreach ($dynamicGallery as $img): 
                $cat = htmlspecialchars($img['category'] ?? 'Resort');
                $img_src = htmlspecialchars($img['image_path']);
                $caption = htmlspecialchars($img['caption'] ?? 'Parijat Resort & Banquet');
            ?>
            <div class="col-lg-4 col-md-6 mb-4 gallery_item" data-category="<?php echo $cat; ?>">
                <div class="gallery_img" style="height: 270px;">
                    <span class="gallery-category-badge"><i class="fa fa-tag mr-1"></i><?php echo $cat; ?></span>
                    <img src="<?php echo $img_src; ?>" alt="<?php echo $caption; ?>" loading="lazy" decoding="async">
                    <div class="hover">
                        <?php if (!empty($caption)): ?>
                            <span class="font-weight-bold small mb-2"><?php echo $caption; ?></span>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-warning font-weight-bold"><i class="fa fa-map-pin mr-1"></i>Parijat Resort</small>
                            <a href="<?php echo $img_src; ?>" class="light" title="<?php echo $caption; ?>" style="background: rgba(255,193,7,0.85); width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; color: #212529 !important;">
                                <i class="fa fa-expand" style="color: #212529 !important;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- WhatsApp Enquiry CTA -->
        <div class="text-center mt-4">
            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? ''); ?>?text=Hello,%20I%20saw%20the%20resort%20photos%20and%20would%20like%20to%20enquire%20about%20booking." target="_blank" class="btn btn-success px-4 py-2 font-weight-bold rounded-pill shadow-sm">
                <i class="fa fa-whatsapp mr-2"></i> Book Your Stay on WhatsApp
            </a>
        </div>
    </div>
</section>

<!-- Filter Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    var filterBtns = document.querySelectorAll("#galleryFilterControls .gallery-filter-btn");
    var galleryItems = document.querySelectorAll("#gallery .gallery_item");

    filterBtns.forEach(function(btn) {
        btn.addEventListener("click", function() {
            filterBtns.forEach(function(b) { b.classList.remove("active"); });
            this.classList.add("active");

            var filter = this.getAttribute("data-filter");

            galleryItems.forEach(function(item) {
                var itemCat = item.getAttribute("data-category");
                if (filter === "all" || itemCat.toLowerCase() === filter.toLowerCase()) {
                    item.style.display = "";
                    setTimeout(function() {
                        item.style.opacity = "1";
                        item.style.transform = "scale(1)";
                    }, 50);
                } else {
                    item.style.opacity = "0";
                    item.style.transform = "scale(0.95)";
                    setTimeout(function() {
                        item.style.display = "none";
                    }, 250);
                }
            });
        });
    });
});
</script>

<?php
include_once 'includes/footer.php';
?>
