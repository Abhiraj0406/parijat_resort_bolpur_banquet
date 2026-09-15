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
            foreach ($rooms as $room): 
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
                        <?php if($amenity['icon_class']): ?>
                            <i class="<?php echo htmlspecialchars($amenity['icon_class']); ?>"></i>
                        <?php else: ?>
                            <i class="fa fa-star"></i>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($amenity['name']); ?>
                    </h4>
                    <p><?php echo htmlspecialchars($amenity['description']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 8. Swimming Pool (Phase 8 - Configurable Timings & Strict Pool Rules) -->
<?php 
$poolTimings = !empty($site_settings['swimming_pool_timings']) ? $site_settings['swimming_pool_timings'] : '10:00 AM – 9:30 PM';
$poolNote = !empty($site_settings['swimming_pool_timing_note']) ? $site_settings['swimming_pool_timing_note'] : 'Timings are subject to seasonal schedule and maintenance.';
?>
<section id="pool" class="about_history_area section_gap bg-light">
    <div class="container">
        <div class="row flex-row-reverse align-items-center">
            <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
                <div class="about_content pl-lg-4">
                    <span class="text-uppercase text-warning font-weight-bold" style="letter-spacing:1px;">Resort Recreation</span>
                    <h2 class="title title_color mt-1">Crystal Clear <br>Swimming Pool</h2>
                    <p class="text-muted">
                        Unwind and refresh in our pristine outdoor swimming pool surrounded by tranquil greenery and comfortable loungers. Perfect for morning laps or a leisurely evening soak with family.
                    </p>

                    <!-- Timing Banner (Configurable from Admin) -->
                    <div class="p-3 mb-4 rounded bg-white shadow-sm border-left border-warning" style="border-left-width: 4px !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small d-block font-weight-bold text-uppercase">Operational Pool Timings</span>
                                <strong class="text-dark" style="font-size: 1.25rem;">
                                    <i class="fa fa-clock-o text-warning mr-2"></i> <?php echo htmlspecialchars($poolTimings); ?>
                                </strong>
                            </div>
                            <span class="badge badge-success px-3 py-2 text-uppercase" style="font-size: 11px;">Open Daily</span>
                        </div>
                        <small class="text-muted d-block mt-1"><?php echo htmlspecialchars($poolNote); ?></small>
                    </div>

                    <!-- Pool Guidelines & Holi Notice -->
                    <h5 class="font-weight-bold text-dark mb-3"><i class="fa fa-life-ring text-primary mr-2"></i>Pool Rules &amp; Hygiene Guidelines</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <div class="d-flex align-items-start small">
                                <i class="fa fa-check-circle text-success mr-2 mt-1"></i>
                                <span>Guests must bring their own swimwear.</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="d-flex align-items-start small">
                                <i class="fa fa-check-circle text-success mr-2 mt-1"></i>
                                <span><strong>Synthetic swimwear</strong> is strictly preferred.</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="d-flex align-items-start small">
                                <i class="fa fa-shower text-primary mr-2 mt-1"></i>
                                <span>Mandatory shower before pool entry.</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="d-flex align-items-start small">
                                <i class="fa fa-ban text-danger mr-2 mt-1"></i>
                                <span>Glassware &amp; food prohibited in pool area.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Holi Special Advisory Alert -->
                    <div class="alert alert-warning border-warning mt-3 mb-0 small text-dark">
                        <strong class="d-block mb-1"><i class="fa fa-exclamation-triangle text-warning mr-1"></i> Special Holi Festival Regulation:</strong>
                        During Holi celebrations, guests must strictly <strong>NOT</strong> enter the swimming pool while playing with colours. All colours must be thoroughly washed off before stepping into the pool.
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 col-md-12">
                <div class="position-relative">
                    <img class="img-fluid rounded shadow-lg w-100" src="image/gallery/03.jpg" alt="Parijat Resort Swimming Pool" style="min-height: 360px; object-fit: cover;">
                    <div class="position-absolute bg-dark text-white p-3 rounded shadow" style="bottom: 20px; left: 20px; max-width: 280px; opacity: 0.95;">
                        <small class="text-warning text-uppercase font-weight-bold">Hygiene Guaranteed</small>
                        <p class="mb-0 small text-white-50">Filtered freshwater with automated pH balancing for safe, pristine swimming.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. Restaurant – Labanya's Heshel Ghar (Phase 7 - Bengali, Chinese & Mughlai Cuisine) -->
<section id="restaurant" class="section_gap" style="background: #faf8f5;">
    <div class="container">
        <div class="section_title text-center mb-5">
            <span class="text-uppercase text-warning font-weight-bold" style="letter-spacing:1px;">Multi-Cuisine Dining Experience</span>
            <h2 class="title_color mt-1">Labanya's Heshel Ghar</h2>
            <p class="mx-auto" style="max-width: 750px;">
                Specializing in authentic <strong>Bengali</strong>, aromatic <strong>Mughlai</strong>, and wok-fresh <strong>Chinese</strong> delicacies. 
                All meals are prepared fresh upon order with pure local ingredients.
            </p>
        </div>

        <!-- Meal Schedule Cards -->
        <div class="row mb-5">
            <!-- Breakfast -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-lg p-3 text-center bg-white border-top border-warning" style="border-top-width: 4px !important;">
                    <i class="fa fa-coffee fa-2x text-warning mb-2"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Breakfast</h5>
                    <div class="badge badge-light border text-dark font-weight-bold px-2 py-1 mb-2 font-monospace">9:00 AM – 10:00 AM</div>
                    <p class="small text-muted mb-0">Luchi-Alur Dom, Puri-Sabji, Toast, Eggs &amp; freshly brewed Darjeeling tea.</p>
                </div>
            </div>

            <!-- Lunch (Thali Service Focus) -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-lg p-3 text-center bg-white border-top border-primary" style="border-top-width: 4px !important;">
                    <i class="fa fa-cutlery fa-2x text-primary mb-2"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Lunch</h5>
                    <div class="badge badge-light border text-dark font-weight-bold px-2 py-1 mb-2 font-monospace">1:00 PM – 3:00 PM</div>
                    <p class="small text-muted mb-0">Grand Bengali &amp; Multi-Cuisine <strong>Thali Service</strong> served fresh in the dining hall.</p>
                </div>
            </div>

            <!-- Evening Snacks -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-lg p-3 text-center bg-white border-top border-success" style="border-top-width: 4px !important;">
                    <i class="fa fa-glass fa-2x text-success mb-2"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Evening Snacks</h5>
                    <div class="badge badge-light border text-dark font-weight-bold px-2 py-1 mb-2 font-monospace">6:00 PM – 8:30 PM</div>
                    <p class="small text-muted mb-0">Chilli Chicken, Pakoras, Crispy Baby Corn. <br><strong class="text-danger">Last Order: 8:00 PM</strong></p>
                </div>
            </div>

            <!-- Dinner -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-lg p-3 text-center bg-white border-top border-danger" style="border-top-width: 4px !important;">
                    <i class="fa fa-moon-o fa-2x text-danger mb-2"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Dinner</h5>
                    <div class="badge badge-light border text-dark font-weight-bold px-2 py-1 mb-2 font-monospace">9:00 PM – 10:30 PM</div>
                    <p class="small text-muted mb-0">Biryani, Roti, Curries. <br><strong class="text-danger">Last Order: 9:00 PM</strong> | Kitchen Closes: 10:30 PM</p>
                </div>
            </div>
        </div>

        <!-- Crucial Dining Guidelines & Policies Box -->
        <div class="card border-0 shadow-sm rounded-lg p-4 mb-5" style="background: #fff;">
            <div class="row align-items-center">
                <div class="col-lg-4 border-right mb-3 mb-lg-0 pr-lg-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa fa-fire text-danger fa-2x mr-3"></i>
                        <div>
                            <h6 class="font-weight-bold text-dark mb-0">100% Freshly Cooked Food</h6>
                            <small class="text-muted">No pre-cooked or frozen meals</small>
                        </div>
                    </div>
                    <p class="small text-muted mb-0">
                        Because we prepare every dish fresh to order, <strong>advance meal orders are requested</strong> during check-in or 1-2 hours prior.
                    </p>
                </div>

                <div class="col-lg-4 border-right mb-3 mb-lg-0 px-lg-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa fa-bell text-warning fa-2x mr-3"></i>
                        <div>
                            <h6 class="font-weight-bold text-dark mb-0">Room Delivery Policy</h6>
                            <small class="text-muted">Breakfast, Snacks &amp; Dinner</small>
                        </div>
                    </div>
                    <p class="small text-muted mb-0">
                        Room delivery is available for breakfast, evening snacks, and dinner. 
                        <strong class="text-dark d-block mt-1">Note: During lunch time, room delivery is NOT available because authentic Thali service is exclusively served in the dining hall.</strong>
                    </p>
                </div>

                <div class="col-lg-4 pl-lg-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa fa-ban text-danger fa-2x mr-3"></i>
                        <div>
                            <h6 class="font-weight-bold text-dark mb-0">Outside Food Policy</h6>
                            <small class="text-danger font-weight-bold">Strictly Prohibited</small>
                        </div>
                    </div>
                    <p class="small text-muted mb-0">
                        To maintain kitchen quality, hygiene standards, and pest control, <strong>outside food is strictly NOT allowed</strong> inside resort rooms or dining premises.
                    </p>
                </div>
            </div>
        </div>

        <!-- Featured Menu Items (Bengali, Chinese & Mughlai) -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="font-weight-bold text-dark mb-0">
                <i class="fa fa-star text-warning mr-2"></i>Signature Culinary Offerings
            </h4>
            <div class="small">
                <span class="badge badge-success px-2 py-1 mr-2"><i class="fa fa-circle mr-1"></i>Veg</span>
                <span class="badge badge-danger px-2 py-1"><i class="fa fa-circle mr-1"></i>Non-Veg</span>
            </div>
        </div>

        <div class="row">
            <?php foreach ($food_items as $item): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-lg p-3 bg-white" style="transition: transform 0.2s ease;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge badge-light border text-muted text-uppercase" style="font-size: 11px;">
                            <?php echo htmlspecialchars($item['category']); ?>
                        </span>
                        <?php if(!empty($item['is_veg'])): ?>
                            <span class="badge badge-success" style="font-size: 11px;"><i class="fa fa-leaf mr-1"></i>Veg</span>
                        <?php else: ?>
                            <span class="badge badge-danger" style="font-size: 11px;">Non-Veg</span>
                        <?php endif; ?>
                    </div>
                    
                    <h5 class="card-title font-weight-bold text-dark mb-1" style="font-size: 1.05rem;">
                        <?php echo htmlspecialchars($item['name']); ?>
                    </h5>
                    <p class="card-text text-muted small mb-3 flex-grow-1" style="line-height: 1.5;">
                        <?php echo htmlspecialchars($item['description']); ?>
                    </p>
                    
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <strong class="text-warning font-weight-bold" style="font-size: 1.15rem;">
                            ₹<?php echo number_format($item['price'], 0); ?>
                        </strong>
                        <small class="text-muted font-italic">Advance Order</small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-3">
            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? ''); ?>?text=Hello,%20I%20would%20like%20to%20enquire%20about%20dining%20at%20Labanya's%20Heshel%20Ghar." target="_blank" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm rounded-pill">
                <i class="fa fa-whatsapp mr-2"></i> Enquire / Order Meals on WhatsApp
            </a>
        </div>
    </div>
</section>

<!-- 10. Banquet / Conference Hall -->
<?php if ($banquet): ?>
<section id="banquet" class="about_history_area section_gap bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about_content pr-lg-3">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge badge-pill badge-warning text-dark font-weight-bold px-3 py-1 text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">
                            <i class="fa fa-star mr-1"></i> Corporate &amp; Celebrations
                        </span>
                    </div>
                    <h2 class="title title_color mb-3" style="font-size: 2.1rem; line-height: 1.3;">
                        <?php echo htmlspecialchars($banquet['name'] ?: 'Banquet & Conference Hall'); ?>
                    </h2>
                    <p class="text-muted leading-relaxed mb-4">
                        <?php echo nl2br(htmlspecialchars($banquet['description'])); ?>
                    </p>

                    <!-- Capacity & Pricing Info (Without inventing fake values) -->
                    <div class="row mb-4">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="p-3 bg-white rounded shadow-sm border-left border-warning" style="border-left-width: 4px !important;">
                                <div class="small text-muted text-uppercase font-weight-bold">Seating Capacity</div>
                                <div class="h5 font-weight-bold text-dark mb-0 mt-1">
                                    <i class="fa fa-users text-warning mr-1"></i>
                                    <?php 
                                    if (!empty($banquet['capacity']) && $banquet['capacity'] > 0) {
                                        echo htmlspecialchars($banquet['capacity']) . ' Guests';
                                    } else {
                                        echo '<span class="text-muted" style="font-size: 0.95rem; font-weight: normal;">Customized per layout</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded shadow-sm border-left border-success" style="border-left-width: 4px !important;">
                                <div class="small text-muted text-uppercase font-weight-bold">Pricing / Tariff</div>
                                <div class="h5 font-weight-bold text-dark mb-0 mt-1">
                                    <i class="fa fa-tag text-success mr-1"></i>
                                    <?php 
                                    if (!empty($banquet['price']) && $banquet['price'] > 0) {
                                        echo '₹' . number_format($banquet['price'], 0);
                                    } elseif (!empty($banquet['pricing_info'])) {
                                        echo htmlspecialchars($banquet['pricing_info']);
                                    } else {
                                        echo '<span class="text-muted" style="font-size: 0.95rem; font-weight: normal;">Packages on Enquiry</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Event Types Badges -->
                    <?php if (!empty($banquet['event_types'])): ?>
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-calendar-check-o text-primary mr-1"></i> Ideal For:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <?php 
                            $event_arr = explode(',', $banquet['event_types']);
                            foreach($event_arr as $ev): 
                                $ev_trim = trim($ev);
                                if (!empty($ev_trim)):
                            ?>
                                <span class="badge badge-secondary px-3 py-2 mr-2 mb-2 font-weight-normal" style="font-size: 0.85rem; background: #e9ecef; color: #495057;">
                                    <i class="fa fa-check-circle-o text-success mr-1"></i><?php echo htmlspecialchars($ev_trim); ?>
                                </span>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Facilities -->
                    <?php if (!empty($banquet['facilities'])): ?>
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-check text-success mr-1"></i> Hall Facilities &amp; Amenities:</h6>
                        <div class="row">
                            <?php 
                            $fac_arr = explode(',', $banquet['facilities']);
                            foreach($fac_arr as $fac): 
                                $fac_trim = trim($fac);
                                if (!empty($fac_trim)):
                            ?>
                                <div class="col-sm-6 mb-2">
                                    <div class="small text-muted d-flex align-items-center">
                                        <i class="fa fa-dot-circle-o text-warning mr-2"></i>
                                        <?php echo htmlspecialchars($fac_trim); ?>
                                    </div>
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex flex-wrap align-items-center gap-3 mt-3">
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? ''); ?>?text=Hello,%20I%20would%20like%20to%20enquire%20about%20booking%20the%20Banquet/Conference%20Hall%20at%20Parijat%20Resort." target="_blank" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm rounded-pill mr-3 mb-2">
                            <i class="fa fa-whatsapp mr-2"></i> Banquet Booking Enquiry
                        </a>
                        <a href="#contact" class="button_hover theme_btn_two mb-2">Contact Desk</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img class="img-fluid rounded shadow-lg w-100" src="<?php echo !empty($banquet['image_path']) ? htmlspecialchars($banquet['image_path']) : 'image/gallery/02.jpg'; ?>" alt="Banquet Hall" style="max-height: 460px; object-fit: cover;">
                    <div class="position-absolute p-3 rounded shadow-sm bg-white" style="bottom: 20px; left: 20px; max-width: 280px; opacity: 0.95;">
                        <h6 class="font-weight-bold text-dark mb-1"><i class="fa fa-glass text-warning mr-1"></i> Tailored Event Catering</h6>
                        <small class="text-muted">Direct catering support from Labanya's Heshel Ghar available for all events.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 11. Gallery -->
<section id="gallery" class="gallery_area section_gap">
    <div class="container">
        <div class="section_title text-center">
            <h2 class="title_color">Resort Gallery</h2>
            <p>Glimpses of Parijat Resort</p>
        </div>
        <div class="row imageGallery1" id="gallery">
            <?php foreach($gallery_images as $img): ?>
            <div class="col-md-4 gallery_item">
                <div class="gallery_img">
                    <img src="<?php echo htmlspecialchars($img['image_path']); ?>" alt="Gallery Image - Parijat Resort Bolpur" loading="lazy" decoding="async" style="width:100%; height:250px; object-fit:cover;">
                    <div class="hover">
                        <a class="light" href="<?php echo htmlspecialchars($img['image_path']); ?>"><i class="fa fa-expand"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
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

<!-- 13. Management & Direct Contact (Phase 11) -->
<section id="contact" class="section_gap bg-light">
    <div class="container">
        <div class="section_title text-center mb-5">
            <span class="badge badge-pill badge-warning text-dark font-weight-bold px-3 py-1 text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">
                <i class="fa fa-phone mr-1"></i> Quick Reservations
            </span>
            <h2 class="title_color font-weight-bold mt-2">Management &amp; Booking Desk</h2>
            <p class="text-muted">Speak directly with our resort management for reservations, banquets and inquiries.</p>
        </div>

        <div class="row align-items-stretch">
            <!-- Leadership Team -->
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="card border-0 shadow-sm rounded-lg p-4 bg-white h-100">
                    <h5 class="font-weight-bold text-dark mb-3"><i class="fa fa-users text-primary mr-2"></i>Resort Leadership</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="p-3 bg-light rounded border-left border-warning" style="border-left-width: 4px !important;">
                                <div class="small text-muted text-uppercase font-weight-bold">Proprietor</div>
                                <h6 class="font-weight-bold text-dark mt-1 mb-0"><?php echo htmlspecialchars($site_settings['proprietor_name'] ?? 'Mr. Ananda Kumar Saha'); ?></h6>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="p-3 bg-light rounded border-left border-primary" style="border-left-width: 4px !important;">
                                <div class="small text-muted text-uppercase font-weight-bold">Finance &amp; Admin Head</div>
                                <h6 class="font-weight-bold text-dark mt-1 mb-0"><?php echo htmlspecialchars($site_settings['finance_head_name'] ?? 'Mr. Arindam Saha'); ?></h6>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded border-left border-success d-flex flex-wrap justify-content-between align-items-center" style="border-left-width: 4px !important;">
                                <div>
                                    <div class="small text-muted text-uppercase font-weight-bold">Resort Manager</div>
                                    <h6 class="font-weight-bold text-dark mt-1 mb-0"><?php echo htmlspecialchars($site_settings['manager_name'] ?? 'Mr. Tonmay Guha'); ?></h6>
                                </div>
                                <a href="tel:<?php echo preg_replace('/[^0-9]/', '', $site_settings['manager_phone'] ?? '+918777513554'); ?>" class="btn btn-outline-success btn-sm font-weight-bold rounded-pill mt-2 mt-sm-0">
                                    <i class="fa fa-phone mr-1"></i> Call: <?php echo htmlspecialchars($site_settings['manager_phone'] ?? '+91 87775 13554'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Call & WhatsApp Desk -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-lg p-4 bg-white h-100 text-center d-flex flex-column justify-content-center">
                    <h5 class="font-weight-bold text-dark mb-2">Direct Booking Hotline</h5>
                    <p class="small text-muted mb-4">Click below to call or chat on WhatsApp for reservations.</p>
                    
                    <div class="d-flex flex-column gap-2">
                        <a href="tel:<?php echo preg_replace('/[^0-9]/', '', $site_settings['phone_primary'] ?? '+919830276364'); ?>" class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm rounded-pill mb-2">
                            <i class="fa fa-phone mr-2"></i> Call: <?php echo htmlspecialchars($site_settings['phone_primary'] ?? '+91 98302 76364'); ?>
                        </a>
                        <a href="tel:<?php echo preg_replace('/[^0-9]/', '', $site_settings['phone_secondary'] ?? '+919830909875'); ?>" class="btn btn-outline-primary btn-block py-2 font-weight-bold rounded-pill mb-2">
                            <i class="fa fa-phone mr-2"></i> Call: <?php echo htmlspecialchars($site_settings['phone_secondary'] ?? '+91 98309 09875'); ?>
                        </a>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? '+919830909875'); ?>?text=Hello,%20I%20would%20like%20to%20enquire%20about%20booking%20at%20Parijat%20Resort%20Bolpur." target="_blank" class="btn btn-success btn-block py-2 font-weight-bold shadow-sm rounded-pill">
                            <i class="fa fa-whatsapp mr-2"></i> WhatsApp: <?php echo htmlspecialchars($site_settings['whatsapp_number'] ?? '+91 98309 09875'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
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
