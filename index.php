<?php
$current_page = "index";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch Active Room Categories with primary image and rates
$stmt = $pdo->query("SELECT c.*, 
        (SELECT image_path FROM room_images WHERE category_id = c.id AND status = 'active' ORDER BY sort_order ASC, id ASC LIMIT 1) as custom_image,
        (SELECT rate_ac FROM room_rates WHERE category_id = c.id AND (rate_ac > 0 OR rate_non_ac > 0) ORDER BY rate_type ASC, id DESC LIMIT 1) as rate_ac,
        (SELECT rate_non_ac FROM room_rates WHERE category_id = c.id AND (rate_ac > 0 OR rate_non_ac > 0) ORDER BY rate_type ASC, id DESC LIMIT 1) as rate_non_ac,
        (SELECT rate_title FROM room_rates WHERE category_id = c.id AND (rate_ac > 0 OR rate_non_ac > 0) ORDER BY rate_type ASC, id DESC LIMIT 1) as rate_title
        FROM room_categories c 
        WHERE c.status = 'active' 
        ORDER BY c.id ASC");
$rooms = $stmt->fetchAll();

// Fetch Amenities
$stmt = $pdo->query("SELECT * FROM amenities ORDER BY name ASC LIMIT 6");
$amenities = $stmt->fetchAll();

// Fetch Restaurant Items (Bengali, Chinese, Mughlai)
$stmt = $pdo->query("SELECT * FROM restaurant_items WHERE status = 'active' ORDER BY FIELD(category, 'Bengali', 'Chinese', 'Mughlai'), id ASC LIMIT 9");
$food_items = $stmt->fetchAll();

// Fetch Banquet Details
$stmt = $pdo->query("SELECT * FROM banquet_details LIMIT 1");
$banquet = $stmt->fetch();

// Fetch Gallery Images
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY id DESC LIMIT 6");
$gallery_images = $stmt->fetchAll();

// Fallback if empty
if (empty($gallery_images)) {
    $gallery_images = [
        ['image_path' => 'image/gallery/07.jpg', 'caption' => 'Resort Cottages'],
        ['image_path' => 'image/gallery/08.jpg', 'caption' => 'Beautiful Resort Courtyard'],
        ['image_path' => 'image/gallery/09.jpg', 'caption' => 'Resort Path at Sunset'],
        ['image_path' => 'image/gallery/10.jpg', 'caption' => 'Main Building Exterior'],
        ['image_path' => 'image/hall1.jpg', 'caption' => 'Banquet Hall Interior'],
        ['image_path' => 'image/pool1.jpg', 'caption' => 'Swimming Pool Deck'],
        ['image_path' => 'image/pool3.jpg', 'caption' => 'Night View of Pool'],
        ['image_path' => 'image/pool4.jpg', 'caption' => 'Pool at Night'],
        ['image_path' => 'image/pool5.jpg', 'caption' => 'Illuminated Pool'],
        ['image_path' => 'image/reception1.jpg', 'caption' => 'Resort Reception'],
        ['image_path' => 'image/reception2.jpg', 'caption' => 'Lobby Area'],
        ['image_path' => 'image/food1.jpg', 'caption' => 'Evening Snacks'],
        ['image_path' => 'image/food2.jpg', 'caption' => 'Authentic Bengali Thali'],
        ['image_path' => 'image/food3.jpg', 'caption' => 'Special Dishes'],
        ['image_path' => 'image/dining_in.jpg', 'caption' => 'Heshel Ghar Dining Area'],
        ['image_path' => 'image/dining_out.jpg', 'caption' => 'Labanya\'s Heshel Ghar']
    ];
}
?>

<section class="banner_area">
    <div class="booking_table d_flex align-items-center">
        <div class="overlay bg-parallax" data-stellar-ratio="0.9" data-stellar-vertical-offset="0" data-background=""></div>
        <div class="container">
            <div class="banner_content text-center" data-aos="zoom-in" data-aos-duration="1000">
                <h6 data-aos="fade-down" data-aos-delay="300">Experience Luxury & Nature</h6>
                <h2 data-aos="fade-up" data-aos-delay="500">Welcome to <?php echo htmlspecialchars($resort_name); ?></h2>
                <p data-aos="fade-up" data-aos-delay="700">A serene getaway in the heart of Bolpur. <br> Perfectly blending modern luxury with natural tranquility.</p>
                <a href="#rooms" class="btn theme_btn button_hover" data-aos="fade-up" data-aos-delay="900">Explore Rooms</a>
            </div>
        </div>
    </div>

    <div class="hotel_booking_area position">
        <div class="container">
            <div class="hotel_booking_table">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h2>Quick<br> Booking</h2>
                    </div>
                    <div class="col-md-9">
                        <div class="boking_table">
                            <form action="contact_process.php" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                <input type="hidden" name="type" value="quick_booking">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="book_tabel_item">
                                            <div class="form-group">
                                                <div class='input-group date' id='datetimepicker11'>
                                                    <input type='text' name="check_in" class="form-control" placeholder="Arrival Date" required/>
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class='input-group date' id='datetimepicker1'>
                                                    <input type='text' name="check_out" class="form-control" placeholder="Departure Date" required/>
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="book_tabel_item">
                                            <div class="form-group" style="margin-bottom: 20px;">
                                                <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="book_tabel_item">
                                            <button type="submit" class="book_now_btn button_hover w-100" style="border:none; border-radius: 5px; margin-top: 10px; padding: 10px 20px;">Request Call</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</section>

<section class="about_history_area section_gap">
    <div class="container">
        <div class="row align-items-stretch">
            <div class="col-lg-6 col-md-12 mb-4 mb-lg-0 d-flex" data-aos="fade-right">
                <div class="about_content p-5 glass-card d-flex flex-column justify-content-center h-100 w-100">
                    <h2 class="title text-white font-weight-bold mb-4" style="line-height: 1.3;">About Us <br>A Haven of Peace</h2>
                    <p class="text-white mb-4" style="font-size: 1.05rem; line-height: 1.7;">Escape the hustle and bustle of city life. Parijat Resort offers an eco-friendly environment filled with lush greenery, providing you the ultimate comfort and relaxation. Whether it's a family vacation or a corporate retreat, we have everything you need.</p>
                    <div>
                        <a href="#contact" class="btn theme_btn button_hover font-weight-bold px-4 py-2 rounded-pill shadow">Contact Us</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 d-flex" data-aos="fade-left" data-aos-delay="200">
                <div class="glass-card p-3 w-100 h-100 d-flex align-items-center justify-content-center">
                    <img class="img-fluid rounded-lg shadow w-100 h-100" src="image/reception1.jpg" alt="About Resort" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>

<section id="rooms" class="accomodation_area section_gap bg-light">
    <div class="container">
        <div class="section_title text-center mb-5" data-aos="fade-up">
            <span class="text-uppercase text-warning font-weight-bold letter-spacing-1">Stay with Luxury & Serenity</span>
            <h2 class="title_color mt-2">Resort Rooms & Cottages</h2>
            <p>19 thoughtfully crafted units across 4 distinct categories. All rooms feature private balconies and convertible AC / Non-AC comfort.</p>
        </div>
        
        <div class="row">
            <?php 
            $demoImages = ['image/room1.jpg', 'image/room2.jpg', 'image/room3.jpg', 'image/room4.jpg', 'image/room5.jpg', 'image/room6.jpg'];
            $roomIndex = 0;
            $delay = 100;
            foreach (array_slice($rooms, 0, 2) as $room): 
                $imgSrc = !empty($room['custom_image']) ? htmlspecialchars($room['custom_image']) : $demoImages[$roomIndex % 6];
                $roomIndex++;
                $facilitiesList = !empty($room['facilities']) ? explode(',', $room['facilities']) : [];
            ?>
            <div class="col-lg-6 col-md-12 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden room-premium-card" style="transition: transform 0.3s ease, box-shadow 0.3s ease; background: #fff;">
                    <div class="row no-gutters h-100">
                        <!-- Room Image Column -->
                        <div class="col-md-5 position-relative" style="min-height: 250px;">
                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" loading="lazy" decoding="async" class="w-100 h-100" style="object-fit: cover; position: absolute; left: 0; top: 0;">
                            
                            <!-- Badges over image -->
                            <div class="position-absolute" style="top: 12px; left: 12px; z-index: 2; display: flex; flex-direction: column;">
                                <div class="mb-2">
                                    <span class="badge badge-dark px-2 py-1 shadow-sm" style="font-size: 11px; letter-spacing: 0.5px;">
                                        <i class="fa fa-key mr-1"></i> <?php echo $room['total_rooms']; ?> Rooms
                                    </span>
                                </div>

                                <?php if (!empty($room['has_balcony'])): ?>
                                <div>
                                    <span class="badge badge-success px-2 py-1 shadow-sm" style="font-size: 11px;">
                                        <i class="fa fa-sun-o mr-1"></i> Balcony
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="position-absolute w-100 text-center" style="bottom: 10px; left: 0; z-index: 2;">
                                <span class="badge badge-warning text-dark px-2 py-1 shadow-sm font-weight-bold" style="font-size: 11px;">
                                    <i class="fa fa-snowflake-o mr-1"></i> AC / Non-AC Convertible
                                </span>
                            </div>
                        </div>

                        <!-- Room Details Column -->
                        <div class="col-md-7 d-flex flex-column justify-content-between p-4">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h4 class="card-title font-weight-bold text-dark mb-1" style="font-size: 1.15rem; line-height: 1.3;">
                                        <?php echo htmlspecialchars($room['name']); ?>
                                    </h4>
                                </div>

                                <p class="card-text text-muted small mb-3" style="line-height: 1.5;">
                                    <?php echo htmlspecialchars($room['description']); ?>
                                </p>

                                <!-- Key Specs Icons -->
                                <div class="bg-light p-2 rounded mb-3 small">
                                    <div class="row no-gutters">
                                        <div class="col-6 mb-1">
                                            <i class="fa fa-users text-warning mr-1"></i> <strong>Occupancy:</strong> <?php echo $room['adult_capacity']; ?> Adults, <?php echo $room['kids_capacity']; ?> Kids (Max <?php echo $room['max_occupancy']; ?>)
                                        </div>
                                        <div class="col-6 mb-1">
                                            <i class="fa fa-building-o text-warning mr-1"></i> <strong>Balcony:</strong> <?php echo $room['has_balcony'] ? 'Private View' : 'No'; ?>
                                        </div>
                                        <div class="col-6">
                                            <i class="fa fa-bed text-warning mr-1"></i> <strong>Extra Person:</strong> ₹<?php echo number_format($room['extra_person_charge'], 0); ?>/night
                                        </div>
                                        <div class="col-6">
                                            <i class="fa fa-thermometer-half text-warning mr-1"></i> <strong>AC Mode:</strong> Convertible
                                        </div>
                                    </div>
                                </div>

                                <!-- Facilities Tags -->
                                <?php if (!empty($facilitiesList)): ?>
                                <div class="mb-3">
                                    <span class="small font-weight-bold text-secondary d-block mb-1">Facilities:</span>
                                    <div class="d-flex flex-wrap" style="gap: 4px;">
                                        <?php foreach (array_slice($facilitiesList, 0, 4) as $facility): ?>
                                            <span class="badge badge-light border text-muted py-1 px-2" style="font-size: 10.5px; font-weight: 500;">
                                                <i class="fa fa-check text-success mr-1"></i><?php echo trim(htmlspecialchars($facility)); ?>
                                            </span>
                                        <?php endforeach; ?>
                                        <?php if (count($facilitiesList) > 4): ?>
                                            <span class="badge badge-light border text-muted py-1 px-2" style="font-size: 10.5px;">+<?php echo count($facilitiesList) - 4; ?> more</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Pricing & Action -->
                            <div class="pt-3 border-top">
                                <div class="mb-3">
                                    <div class="text-warning font-weight-bold" style="font-size: 0.95rem;">
                                        <i class="fa fa-tag mr-1"></i> Tariff on Request
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 11px;">Contact us for best rates</small>
                                </div>

                                <div class="d-flex w-100" style="gap: 8px;">
                                    <a href="booking.php?category=<?php echo $room['id']; ?>" class="btn btn-sm btn-dark text-white py-2 rounded-pill font-weight-bold shadow-sm flex-fill text-center">
                                        Book Online
                                    </a>
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? ''); ?>?text=Hello,%20I%20am%20interested%20in%20booking%20<?php echo urlencode($room['name']); ?>%20at%20Parijat%20Resort." target="_blank" class="btn btn-sm btn-success text-white py-2 rounded-pill font-weight-bold shadow-sm flex-fill text-center" title="Chat on WhatsApp">
                                        <i class="fa fa-whatsapp mr-1"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                $delay += 200;
            endforeach; 
            ?>
        </div>

        <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="200">
            <a href="accomodation.php" class="btn theme_btn button_hover font-weight-bold px-4 py-2 rounded-pill shadow">
                View All Categories
            </a>
        </div>
    </div>
</section>

<section class="facilities_area section_gap">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
    <div class="container">
        <div class="section_title text-center" data-aos="fade-up">
            <h2 class="title_w">Resort Facilities</h2>
            <p>Everything you need for a perfect stay</p>
        </div>
        <div class="row mb_30">
            <?php 
            $f_delay = 100;
            foreach ($amenities as $amenity): 
            ?>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="<?php echo $f_delay; ?>">
                <div class="facilities_item glass-card text-center p-4 h-100">
                    <?php 
                    if($amenity['icon_class']){
                        $ic = trim($amenity['icon_class']);
                        // Remove potential duplicates
                        $ic = str_replace(['bi ', 'fa '], '', $ic);
                        
                        if(strpos($ic, 'bi-') === 0) {
                            echo '<i class="bi ' . htmlspecialchars($ic) . ' fa-3x text-info mb-3"></i>';
                        } elseif(strpos($ic, 'fa-') === 0) {
                            echo '<i class="fa ' . htmlspecialchars($ic) . ' fa-3x text-info mb-3"></i>';
                        } else {
                            echo '<i class="' . htmlspecialchars($ic) . ' fa-3x text-info mb-3"></i>';
                        }
                    } else {
                        echo '<i class="fa fa-star fa-3x text-info mb-3"></i>';
                    }
                    ?>
                    <h5 class="text-white font-weight-bold mb-2">
                        <?php echo htmlspecialchars($amenity['name']); ?>
                    </h5>
                    <p class="text-white small mb-0"><?php echo htmlspecialchars($amenity['description']); ?></p>
                </div>
            </div>
            <?php 
                $f_delay += 100;
            endforeach; 
            ?>
        </div>
    </div>
</section>

<section id="experiences" class="section_gap bg-light">
    <div class="container">
        <div class="section_title text-center mb-5" data-aos="fade-up">
            <span class="badge badge-pill badge-warning text-dark font-weight-bold px-3 py-1 text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                <i class="fa fa-star mr-1"></i> Signature Experiences
            </span>
            <h2 class="title_color font-weight-bold mt-2">Dine, Celebrate & Unwind</h2>
        </div>
        
        <div class="row align-items-stretch">
            <!-- Dining -->
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
                    <img src="image/dining_out.jpg" class="card-img-top" alt="Labanya's Heshel Ghar" style="height: 220px; object-fit: cover;">
                    <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="font-weight-bold mb-3">Labanya's Heshel Ghar</h4>
                            <p class="text-muted small">Experience authentic Bengali thalis, sizzling Chinese woks, and rich Mughlai dishes crafted by expert chefs.</p>
                        </div>
                        <a href="dining.php" class="btn btn-outline-warning rounded-pill mt-3 font-weight-bold">Explore Menu</a>
                    </div>
                </div>
            </div>

            <!-- Banquet -->
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="250">
                <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
                    <img src="<?php echo !empty($banquet['image_path']) ? htmlspecialchars($banquet['image_path']) : 'image/hall1.jpg'; ?>" class="card-img-top" alt="Banquet Hall" style="height: 220px; object-fit: cover;">
                    <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="font-weight-bold mb-3">Grand Banquet & Lawns</h4>
                            <p class="text-muted small">Host your dream wedding, corporate event, or family gathering in our spacious fully AC banquet hall and open lawns.</p>
                        </div>
                        <a href="banquet.php" class="btn btn-outline-warning rounded-pill mt-3 font-weight-bold">Plan Event</a>
                    </div>
                </div>
            </div>

            <!-- Pool & Nature -->
            <div class="col-lg-4 col-md-6 mb-4 mx-auto" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
                    <img src="image/pool1.jpg" class="card-img-top" alt="Swimming Pool" style="height: 220px; object-fit: cover;">
                    <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="font-weight-bold mb-3">Swimming Pool & Gardens</h4>
                            <p class="text-muted small">Take a refreshing dip in our crystal clear pool or stroll through our manicured gardens to reconnect with nature.</p>
                        </div>
                        <a href="gallery.php" class="btn btn-outline-warning rounded-pill mt-3 font-weight-bold">View Gallery</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="gallery" class="gallery_area section_gap">
    <div class="container">
        <div class="section_title text-center mb-5">
            <span class="badge badge-pill badge-warning text-dark font-weight-bold px-3 py-1 text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                <i class="fa fa-camera mr-1"></i> Visual Tour
            </span>
            <h2 class="title_color font-weight-bold mt-2">Glimpses of Paradise</h2>
        </div>
        <div class="row imageGallery1" id="gallery">
            <?php foreach(array_slice($gallery_images, 0, 4) as $img): ?>
            <div class="col-lg-3 col-md-6 mb-4 gallery_item">
                <div class="gallery_img rounded shadow-sm overflow-hidden" style="height: 200px;">
                    <img src="<?php echo htmlspecialchars($img['image_path']); ?>" alt="Gallery Image - Parijat Resort Bolpur" loading="lazy" decoding="async" class="w-100 h-100" style="object-fit:cover; transition: transform 0.4s;">
                    <div class="hover">
                        <a class="light" href="<?php echo htmlspecialchars($img['image_path']); ?>"><i class="fa fa-expand"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="gallery.php" class="btn theme_btn button_hover font-weight-bold px-4 py-2">
                <i class="fa fa-picture-o mr-2"></i> View Full Gallery
            </a>
        </div>
    </div>
</section>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, background 0.3s ease;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
}
.glass-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.2);
}
</style>
<section class="glass_section section_gap" style="position: relative;">
    <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: url('image/pool3.jpg') no-repeat center center/cover; z-index: 1;"></div>
    <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.6); z-index: 2;"></div>
    
    <div class="container" style="position: relative; z-index: 3;">
        <div class="text-center mb-5" data-aos="zoom-in">
            <h2 class="text-white font-weight-bold mb-3" style="font-size: 2.5rem; text-shadow: 2px 2px 5px rgba(0,0,0,0.5);">Why Choose Parijat Resort?</h2>
            <p class="text-white" style="font-size: 1.1rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">Discover the perfect blend of modern luxury and serene nature.</p>
        </div>
        
        <div class="row justify-content-center">
            <!-- Feature 1 -->
            <div class="col-lg-4 col-md-6 mb-4" data-aos="flip-left" data-aos-delay="100">
                <div class="glass-card text-center p-4 h-100">
                    <i class="fa fa-leaf fa-3x text-success mb-3"></i>
                    <h5 class="text-white font-weight-bold mb-2">Nature's Lap</h5>
                    <p class="text-white small mb-0">Surrounded by lush green gardens and a peaceful environment.</p>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="col-lg-4 col-md-6 mb-4" data-aos="flip-left" data-aos-delay="300">
                <div class="glass-card text-center p-4 h-100">
                    <i class="fa fa-bed fa-3x text-info mb-3"></i>
                    <h4 class="text-white font-weight-bold mb-3">Luxury Rooms</h4>
                    <p class="text-white small mb-0">19 Premium AC & Non-AC convertible spacious rooms.</p>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="col-lg-4 col-md-6 mb-4" data-aos="flip-left" data-aos-delay="500">
                <div class="glass-card text-center p-4 h-100">
                    <i class="fa fa-glass fa-3x text-warning mb-3"></i>
                    <h5 class="text-white font-weight-bold mb-2">Event Spaces</h5>
                    <p class="text-white small mb-0">Grand Banquet Hall & open lawns for your celebrations.</p>
                </div>
            </div>
            
            <!-- Feature 4 -->
            <div class="col-lg-4 col-md-6 mb-4 mx-auto" data-aos="flip-left" data-aos-delay="700">
                <div class="glass-card text-center p-4 h-100">
                    <i class="fa fa-cutlery fa-3x text-danger mb-3"></i>
                    <h4 class="text-white font-weight-bold mb-3">Fine Dining</h4>
                    <p class="text-white small mb-0">Authentic Bengali, Chinese & Mughlai Cuisine.</p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4" data-aos="zoom-in" data-aos-delay="900">
            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? ''); ?>" target="_blank" class="btn btn-success font-weight-bold px-4 py-2 rounded-pill shadow-lg">
                <i class="fa fa-whatsapp mr-2"></i> Chat with Us
            </a>
        </div>
    </div>
</section>

<section class="testimonial_area section_gap bg-light">
    <div class="container">
        <div class="section_title text-center mb-5">
            <span class="badge badge-pill badge-warning text-dark font-weight-bold px-3 py-1 text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                <i class="fa fa-heart mr-1"></i> Guest Experiences
            </span>
            <h2 class="title_color font-weight-bold mt-2">What Our Guests Say</h2>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-lg p-4 h-100 bg-white" style="border-top: 4px solid #2e7d32 !important;">
                    <div class="d-flex mb-3 text-warning">
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    </div>
                    <p class="text-muted font-italic mb-4">"A perfect weekend getaway! The resort is beautifully maintained, and the pool area is so relaxing. The Bengali cuisine at the restaurant was absolutely delicious."</p>
                    <div class="d-flex align-items-center mt-auto">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-3" style="width: 45px; height: 45px; font-size: 1.2rem;">S</div>
                        <div>
                            <h6 class="mb-0 font-weight-bold text-dark">Saurav Mukhopadhyay</h6>
                            <small class="text-muted">Kolkata</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-lg p-4 h-100 bg-white" style="border-top: 4px solid #2e7d32 !important;">
                    <div class="d-flex mb-3 text-warning">
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    </div>
                    <p class="text-muted font-italic mb-4">"We booked the banquet for a family function. The management was incredibly supportive, and the catering was top-notch. Highly recommended for events!"</p>
                    <div class="d-flex align-items-center mt-auto">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-3" style="width: 45px; height: 45px; font-size: 1.2rem;">P</div>
                        <div>
                            <h6 class="mb-0 font-weight-bold text-dark">Priya Banerjee</h6>
                            <small class="text-muted">Burdwan</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="card border-0 shadow-sm rounded-lg p-4 h-100 bg-white" style="border-top: 4px solid #2e7d32 !important;">
                    <div class="d-flex mb-3 text-warning">
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i>
                    </div>
                    <p class="text-muted font-italic mb-4">"Very peaceful environment near Shantiniketan. The rooms are spacious with nice balconies. Great place to unplug from city life."</p>
                    <div class="d-flex align-items-center mt-auto">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-3" style="width: 45px; height: 45px; font-size: 1.2rem;">A</div>
                        <div>
                            <h6 class="mb-0 font-weight-bold text-dark">Anirban Roy</h6>
                            <small class="text-muted">Durgapur</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section_gap bg-dark text-white text-center py-5">
    <div class="container">
        <h3 class="mb-2 text-white font-weight-bold">Resort Rules &amp; Regulations</h3>
        <p class="mb-4 text-white-50" style="max-width: 650px; margin: 0 auto;">
            Please review our 18 resort guidelines regarding check-in (12:30 PM), check-out (11:00 AM), dining schedules, swimming pool etiquette, and cancellation terms.
        </p>
        <a href="rules.php" class="btn theme_btn button_hover font-weight-bold shadow px-4 py-2">
            <i class="fa fa-book mr-2"></i>View Dedicated Rules &amp; Regulations Page
        </a>
    </div>
</section>

<div class="whatsapp-cta" style="position:fixed; bottom:20px; right:20px; z-index:9999;">
    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? ''); ?>" target="_blank" class="d-flex align-items-center bg-success text-white px-3 py-2 rounded-pill shadow text-decoration-none">
        <i class="fa fa-whatsapp fa-2x mr-2"></i>
        <strong>Chat with Us</strong>
    </a>
</div>

<style>
.whatsapp-cta a:hover {
    background-color: #218838 !important;
    transform: scale(1.05);
    transition: all 0.3s;
}
</style>

<?php require_once 'includes/footer.php'; ?>
