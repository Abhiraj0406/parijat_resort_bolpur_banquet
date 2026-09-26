<?php
$current_page = "gallery";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch gallery images managed dynamically by admin
$stmt = $pdo->query("SELECT * FROM gallery WHERE status = 'active' ORDER BY sort_order ASC, id ASC");
$dynamicGallery = $stmt->fetchAll();

// Comprehensive fallback if table is empty
if (empty($dynamicGallery)) {
    $dynamicGallery = [
        // Resort & Architecture
        ['image_path' => 'image/entrance_slide_1.jpeg', 'caption' => 'Grand Resort Entrance & Welcoming Gate', 'category' => 'Resort'],
        ['image_path' => 'image/entrance_slide_2.jpeg', 'caption' => 'Resort Entrance Pathway & Greenery', 'category' => 'Resort'],
        ['image_path' => 'image/entrance_slide_3.jpeg', 'caption' => 'Lush Green Front Lawn & Walkway', 'category' => 'Resort'],
        ['image_path' => 'image/entrance_slide_4.jpeg', 'caption' => 'Serene Resort Compound & Gardens', 'category' => 'Resort'],
        ['image_path' => 'image/premium_building_1.jpeg', 'caption' => 'Premium Accommodation Building (Morning View)', 'category' => 'Resort'],
        ['image_path' => 'image/premium_building_2.jpeg', 'caption' => 'Balconies & Landscaped Garden Facade', 'category' => 'Resort'],
        ['image_path' => 'image/premium_building_3.jpeg', 'caption' => 'Serene Greenery & Scenic Grounds', 'category' => 'Resort'],
        ['image_path' => 'image/premium_building_4.jpeg', 'caption' => 'Illuminated Evening Facade', 'category' => 'Resort'],
        ['image_path' => 'image/premium_building_5.jpeg', 'caption' => 'Premium Building (Night Ambience & Lighting)', 'category' => 'Resort'],
        ['image_path' => 'image/premium_building_6.jpeg', 'caption' => 'Peaceful & Secure Night Atmosphere', 'category' => 'Resort'],
        ['image_path' => 'image/about_bg.jpg', 'caption' => 'Tranquil Resort Surroundings in Bolpur', 'category' => 'Resort'],

        // Rooms & Suites
        ['image_path' => 'image/cottage_slide1.jpg', 'caption' => 'Deluxe Cottage Room (King Bed Setup)', 'category' => 'Rooms'],
        ['image_path' => 'image/cottage_slide2.jpg', 'caption' => 'Deluxe Cottage Sit-out & Side View', 'category' => 'Rooms'],
        ['image_path' => 'image/cottage_slide3.jpg', 'caption' => 'Deluxe Cottage Modern Bathroom', 'category' => 'Rooms'],
        ['image_path' => 'image/ground_slide_1.jpeg', 'caption' => 'Deluxe Premium Ground Floor Suite', 'category' => 'Rooms'],
        ['image_path' => 'image/ground_slide_2.jpeg', 'caption' => 'Ground Floor Premium Bed & Interior', 'category' => 'Rooms'],
        ['image_path' => 'image/ground_slide_3.jpeg', 'caption' => 'Ground Floor Balcony with Garden View', 'category' => 'Rooms'],
        ['image_path' => 'image/ground_slide_4.jpeg', 'caption' => 'Modern Bathroom with Geyser & Toiletries', 'category' => 'Rooms'],
        ['image_path' => 'image/ground_slide_5.jpeg', 'caption' => 'Deluxe Room Seating & Lighting', 'category' => 'Rooms'],
        ['image_path' => 'image/first_slide_1.jpeg', 'caption' => 'Deluxe Premium First Floor Suite', 'category' => 'Rooms'],
        ['image_path' => 'image/first_slide_2.jpeg', 'caption' => 'First Floor Private Balcony & Pool View', 'category' => 'Rooms'],
        ['image_path' => 'image/first_slide_3.jpeg', 'caption' => 'First Floor Suite Interior & LED TV', 'category' => 'Rooms'],
        ['image_path' => 'image/family_slide1.jpg', 'caption' => 'Family Premium Four Sharing Suite', 'category' => 'Rooms'],
        ['image_path' => 'image/family_slide2.jpg', 'caption' => 'Family Suite Dual Double Bed Layout', 'category' => 'Rooms'],
        ['image_path' => 'image/family_slide3.jpg', 'caption' => 'Family Suite Wardrobe & Amenities', 'category' => 'Rooms'],
        ['image_path' => 'image/family_slide4.jpg', 'caption' => 'Family Suite Modern Washroom', 'category' => 'Rooms'],

        // Swimming Pool & Gardens
        ['image_path' => 'image/pool_slide_1.jpeg', 'caption' => 'Crystal Clear Swimming Pool', 'category' => 'Pool'],
        ['image_path' => 'image/pool_slide_2.jpeg', 'caption' => 'Poolside Sun Loungers & Relaxation Deck', 'category' => 'Pool'],
        ['image_path' => 'image/pool_slide_3.jpeg', 'caption' => 'Scenic Pool Deck & Surrounding Gardens', 'category' => 'Pool'],
        ['image_path' => 'image/pool_slide_4.jpeg', 'caption' => 'Poolside Shaded Seating Area', 'category' => 'Pool'],
        ['image_path' => 'image/pool_slide_5.jpeg', 'caption' => 'Lush Green Poolside Grounds', 'category' => 'Pool'],
        ['image_path' => 'image/pool_slide_6.jpeg', 'caption' => 'Poolside Palm Trees & Pathway', 'category' => 'Pool'],
        ['image_path' => 'image/pool_slide_7.jpeg', 'caption' => 'Evening Pool Reflections', 'category' => 'Pool'],
        ['image_path' => 'image/pool_slide_8.jpeg', 'caption' => 'Daytime Pool Oasis', 'category' => 'Pool'],
        ['image_path' => 'image/pool_slide_9.jpeg', 'caption' => 'Pool & Garden Landscape', 'category' => 'Pool'],
        ['image_path' => 'image/pool_slide_10.jpeg', 'caption' => 'Pool Deck Perspective', 'category' => 'Pool'],
        ['image_path' => 'image/pool_slide_12.jpeg', 'caption' => 'Swimming Pool Panoramic View', 'category' => 'Pool'],
        ['image_path' => 'image/pool_bg.jpg', 'caption' => 'Night Pool Illumination & Lights', 'category' => 'Pool'],

        // Banquet & Events
        ['image_path' => 'image/banquet_slide_1.jpeg', 'caption' => 'Parijat AC Banquet & Conference Hall Front View', 'category' => 'Banquet'],
        ['image_path' => 'image/banquet_slide_2.jpeg', 'caption' => 'AC Conference & Banquet Setup', 'category' => 'Banquet'],
        ['image_path' => 'image/banquet_slide_3.jpeg', 'caption' => 'Banquet Seating Arrangement & Chairs', 'category' => 'Banquet'],
        ['image_path' => 'image/banquet_slide_4.jpeg', 'caption' => 'Spacious Event Stage & Hall', 'category' => 'Banquet'],
        ['image_path' => 'image/banquet_slide_5.jpeg', 'caption' => 'Banquet Lighting & Festive Decor', 'category' => 'Banquet'],
        ['image_path' => 'image/banquet_slide_6.jpeg', 'caption' => 'Conference Hall Table & Seminar Setup', 'category' => 'Banquet'],
        ['image_path' => 'image/banquet_slide_7.jpeg', 'caption' => 'Event Reception & Hall Interior', 'category' => 'Banquet'],
        ['image_path' => 'image/banquet_slide_8.jpeg', 'caption' => 'Grand Celebration Hall Space', 'category' => 'Banquet'],
        ['image_path' => 'image/banquet_slide_9.jpeg', 'caption' => 'Banquet Hall Dining & Buffet Area', 'category' => 'Banquet'],
        ['image_path' => 'image/banquet_slide_10.jpeg', 'caption' => 'Fully AC Conference Facilities', 'category' => 'Banquet'],
        ['image_path' => 'image/banquet_slide_11.jpeg', 'caption' => 'Premium Banquet Hall Atmosphere', 'category' => 'Banquet'],

        // Dining & Heshel Ghar
        ['image_path' => 'image/dining_out.jpg', 'caption' => 'Labanya\'s Heshel Ghar Restaurant Exterior', 'category' => 'Dining'],
        ['image_path' => 'image/dining_in.jpg', 'caption' => 'Heshel Ghar Indoor Dining Hall', 'category' => 'Dining'],
        ['image_path' => 'image/restaurant_slide_1.jpeg', 'caption' => 'Restaurant Exterior & Courtyard Setup', 'category' => 'Dining'],
        ['image_path' => 'image/restaurant_slide_2.jpeg', 'caption' => 'Spacious Multi-Cuisine Dining Hall', 'category' => 'Dining'],
        ['image_path' => 'image/restaurant_slide_3.jpeg', 'caption' => 'Heshel Ghar Restaurant Interior View', 'category' => 'Dining'],
        ['image_path' => 'image/restaurant_slide_4.jpeg', 'caption' => 'Garden & Restaurant View', 'category' => 'Dining'],
        ['image_path' => 'image/restaurant_slide_5.jpeg', 'caption' => 'Outdoor & Courtyard Dining', 'category' => 'Dining'],
        ['image_path' => 'image/food2.jpg', 'caption' => 'Authentic Bengali Fish Thali Feast', 'category' => 'Dining'],
        ['image_path' => 'image/chinese_food.jpg', 'caption' => 'Crispy Wok-Tossed Chilli Chicken', 'category' => 'Dining'],
        ['image_path' => 'image/roll_kabab.jpg', 'caption' => 'Kolkata Kathi Rolls & Sizzling Kebabs', 'category' => 'Dining'],

        // Reception & Lobby
        ['image_path' => 'image/reception1.jpg', 'caption' => 'Resort Front Office & Reception Desk', 'category' => 'Reception'],
        ['image_path' => 'image/reception2.jpg', 'caption' => 'Welcome Lobby & Guest Lounge', 'category' => 'Reception']
    ];
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
            <span class="badge badge-pill badge-warning text-dark font-weight-bold px-3 py-2 text-uppercase text-wrap" style="font-size: 0.8rem; letter-spacing: 1px;">
                <i class="fa fa-camera mr-1"></i> Visual Tour
            </span>
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
        </div>

        <!-- Gallery Grid -->
        <div class="row imageGallery1" id="gallery">
            <?php foreach ($dynamicGallery as $img): 
                $cat = htmlspecialchars($img['category'] ?? 'Resort');
                $img_src = htmlspecialchars($img['image_path']);
                $caption = htmlspecialchars($img['caption'] ?? 'Parijat Resort Bolpur');
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
