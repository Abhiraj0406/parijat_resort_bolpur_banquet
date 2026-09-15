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
?>

<!-- 2. Hero Section -->
<section class="banner_area">
    <div class="booking_table d_flex align-items-center">
        <div class="overlay bg-parallax" data-stellar-ratio="0.9" data-stellar-vertical-offset="0" data-background=""></div>
        <div class="container">
            <div class="banner_content text-center">
                <h6>Experience Luxury & Nature</h6>
                <h2>Welcome to <?php echo htmlspecialchars($resort_name); ?></h2>
                <p>A serene getaway in the heart of Bolpur. <br> Perfectly blending modern luxury with natural tranquility.</p>
                <a href="#rooms" class="btn theme_btn button_hover">Explore Rooms</a>
            </div>
        </div>
    </div>

    <!-- 6. Check Availability / Booking Enquiry (Floating over hero) -->
    <div class="hotel_booking_area position">
        <div class="container">
            <div class="hotel_booking_table">
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
                                    <button type="submit" class="book_now_btn button_hover w-100" style="border:none;">Request Call</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>

<!-- 3. Resort Introduction -->
<section class="about_history_area section_gap">
    <div class="container">
        <div class="row">
            <div class="col-md-6 d_flex align-items-center">
                <div class="about_content">
                    <h2 class="title title_color">About Us <br>A Haven of Peace</h2>
                    <p>Escape the hustle and bustle of city life. Parijat Resort offers an eco-friendly environment filled with lush greenery, providing you the ultimate comfort and relaxation. Whether it's a family vacation or a corporate retreat, we have everything you need.</p>
                    <a href="#contact" class="button_hover theme_btn_two">Contact Us</a>
                </div>
            </div>
            <div class="col-md-6">
                <img class="img-fluid rounded shadow" src="image/about_bg.jpg" alt="About Resort">
            </div>
        </div>
    </div>
</section>

<!-- 4 & 5. Room Categories & Rates (Phase 5 - 19 Total Rooms across 4 Categories) -->
<section id="rooms" class="accomodation_area section_gap bg-light">
    <div class="container">
        <div class="section_title text-center mb-5">
            <span class="text-uppercase text-warning font-weight-bold letter-spacing-1">Stay with Luxury & Serenity</span>
            <h2 class="title_color mt-2">Resort Rooms & Cottages</h2>
            <p>19 thoughtfully crafted units across 4 distinct categories. All rooms feature private balconies and convertible AC / Non-AC comfort.</p>
        </div>
        
        <div class="row">
            <?php 
            $demoImages = ['image/room1.jpg', 'image/room2.jpg', 'image/room3.jpg', 'image/room4.jpg'];
            $roomIndex = 0;
            foreach (array_slice($rooms, 0, 2) as $room): 
                $imgSrc = !empty($room['custom_image']) ? htmlspecialchars($room['custom_image']) : $demoImages[$roomIndex % 4];
                $roomIndex++;
                $facilitiesList = !empty($room['facilities']) ? explode(',', $room['facilities']) : [];
            ?>
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden room-premium-card" style="transition: transform 0.3s ease, box-shadow 0.3s ease; background: #fff;">
                    <div class="row no-gutters h-100">
                        <!-- Room Image Column -->
                        <div class="col-md-5 position-relative" style="min-height: 250px;">
                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" loading="lazy" decoding="async" class="w-100 h-100" style="object-fit: cover; position: absolute; left: 0; top: 0;">
                            
                            <!-- Badges over image -->
                            <div class="position-absolute" style="top: 12px; left: 12px; z-index: 2;">
                                <span class="badge badge-dark px-2 py-1 shadow-sm" style="font-size: 11px; letter-spacing: 0.5px;">
                                    <i class="fa fa-key mr-1"></i> <?php echo $room['total_rooms']; ?> Rooms
                                </span>
                            </div>

                            <?php if (!empty($room['has_balcony'])): ?>
                            <div class="position-absolute" style="top: 12px; right: 12px; z-index: 2;">
                                <span class="badge badge-success px-2 py-1 shadow-sm" style="font-size: 11px;">
                                    <i class="fa fa-sun-o mr-1"></i> Balcony
                                </span>
                            </div>
                            <?php endif; ?>

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
                            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if (!empty($room['rate_ac']) && $room['rate_ac'] > 0): ?>
                                        <div class="font-weight-bold text-dark" style="font-size: 1.1rem;">
                                            ₹<?php echo number_format($room['rate_ac'], 0); ?> <small class="text-muted font-weight-normal">/ night (AC)</small>
                                        </div>
                                        <?php if (!empty($room['rate_non_ac']) && $room['rate_non_ac'] > 0): ?>
                                            <div class="small text-success font-weight-bold">
                                                ₹<?php echo number_format($room['rate_non_ac'], 0); ?> <small class="text-muted">/ night (Non-AC)</small>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="text-warning font-weight-bold" style="font-size: 0.95rem;">
                                            <i class="fa fa-tag mr-1"></i> Tariff on Request
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 11px;">Seasonal & Weekend rates apply</small>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex" style="gap: 8px;">
                                    <a href="booking.php?category=<?php echo $room['id']; ?>" class="btn btn-sm btn-dark text-white px-3 py-2 rounded-pill font-weight-bold shadow-sm">
                                        Enquire Room
                                    </a>
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? ''); ?>?text=Hello,%20I%20am%20interested%20in%20booking%20<?php echo urlencode($room['name']); ?>%20at%20Parijat%20Resort." target="_blank" class="btn btn-sm btn-success text-white px-2 py-2 rounded-pill shadow-sm" title="Chat on WhatsApp">
                                        <i class="fa fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="accomodation.php" class="btn theme_btn button_hover font-weight-bold text-uppercase px-4 py-2">
                View All Room Details & Tariffs &rarr;
            </a>
        </div>
    </div>
</section>

<!-- 7. Resort Facilities -->
<section class="facilities_area section_gap">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
    <div class="container">
        <div class="section_title text-center">
            <h2 class="title_w">Resort Facilities</h2>
            <p>Everything you need for a perfect stay</p>
        </div>
        <div class="row mb_30">
            <?php foreach ($amenities as $amenity): ?>
            <div class="col-lg-4 col-md-6">
                <div class="facilities_item">
                    <h4 class="sec_h4">
                        <?php 
                        if($amenity['icon_class']){
                            $ic = trim($amenity['icon_class']);
                            if(strpos($ic, 'bi-') === 0) {
                                echo '<i class="bi ' . htmlspecialchars($ic) . ' mr-2 text-primary"></i>';
                            } elseif(strpos($ic, 'fa-') === 0) {
                                echo '<i class="fa ' . htmlspecialchars($ic) . ' mr-2 text-primary"></i>';
                            } else {
                                echo '<i class="' . htmlspecialchars($ic) . ' mr-2 text-primary"></i>';
                            }
                        } else {
                            echo '<i class="fa fa-star mr-2 text-primary"></i>';
                        }
                        ?>
                        <?php echo htmlspecialchars($amenity['name']); ?>
                    </h4>
                    <p><?php echo htmlspecialchars($amenity['description']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Experience & Offerings Teaser Grid -->
<section id="experiences" class="section_gap bg-light">
    <div class="container">
        <div class="section_title text-center mb-5">
            <span class="badge badge-pill badge-warning text-dark font-weight-bold px-3 py-1 text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                <i class="fa fa-star mr-1"></i> Resort Experiences
            </span>
            <h2 class="title_color font-weight-bold mt-2">Dine, Celebrate & Unwind</h2>
        </div>
        
        <div class="row align-items-stretch">
            <!-- Dining Teaser -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-lg overflow-hidden h-100" style="transition: transform 0.3s ease;">
                    <img src="image/gallery/03.jpg" class="card-img-top" alt="Dining" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-4 text-center d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-2">Labanya's Heshel Ghar</h4>
                            <p class="text-muted small mb-4">Experience authentic Bengali, Chinese, and Mughlai culinary delights in our premium restaurant.</p>
                        </div>
                        <a href="dining.php" class="btn btn-outline-warning font-weight-bold rounded-pill text-dark">Explore Menu</a>
                    </div>
                </div>
            </div>
            
            <!-- Banquet Teaser -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-lg overflow-hidden h-100" style="transition: transform 0.3s ease;">
                    <img src="<?php echo !empty($banquet['image_path']) ? htmlspecialchars($banquet['image_path']) : 'image/gallery/02.jpg'; ?>" class="card-img-top" alt="Banquet" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-4 text-center d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-2">Banquet & Events</h4>
                            <p class="text-muted small mb-4">The perfect elegant venue for weddings, corporate seminars, and grand family celebrations.</p>
                        </div>
                        <a href="banquet.php" class="btn btn-outline-warning font-weight-bold rounded-pill text-dark">View Details</a>
                    </div>
                </div>
            </div>
            
            <!-- Pool Teaser -->
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="card border-0 shadow-sm rounded-lg overflow-hidden h-100" style="transition: transform 0.3s ease;">
                    <img src="image/gallery/05.jpg" class="card-img-top" alt="Swimming Pool" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-4 text-center d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-2">Swimming Pool</h4>
                            <p class="text-muted small mb-4">Unwind and refresh in our crystal clear pool. Open daily from 10:00 AM to 9:30 PM.</p>
                        </div>
                        <a href="rules.php" class="btn btn-outline-warning font-weight-bold rounded-pill text-dark">Pool Rules</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 11. Gallery Teaser -->
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

<!-- 11.5 Testimonials -->
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
                <div class="card border-0 shadow-sm rounded-lg p-4 h-100 bg-white" style="border-top: 4px solid #f3c300 !important;">
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
                <div class="card border-0 shadow-sm rounded-lg p-4 h-100 bg-white" style="border-top: 4px solid #f3c300 !important;">
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
                <div class="card border-0 shadow-sm rounded-lg p-4 h-100 bg-white" style="border-top: 4px solid #f3c300 !important;">
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

<!-- 12. Rules & Policies Snippet -->
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


<!-- 13. Contact / WhatsApp CTA -->
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
