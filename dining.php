<?php
$page_title = "Labanya's Heshel Ghar - Multi-Cuisine Dining at Parijat Resort";
$current_page = "restaurant";
$meta_description = "Experience authentic Bengali, delicious Roll, Kebab, and wok-fresh Chinese delicacies at Labanya's Heshel Ghar, Parijat Resort & Banquet.";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch signature food items for the 3 cuisines: Bengali, Chinese, Roll, Kebab
$signature_items = [
    [
        'category' => 'Bengali',
        'name' => 'Kolkata Style Chicken / Mutton Kosha',
        'description' => 'Slow-cooked spicy meat delicacy infused with whole spices and mustard oil, served with Bengali thali accompaniments.',
        'price' => 260,
        'is_veg' => 0,
        'image_path' => 'image/food2.jpg' // Authentic Bengali Thali feast image from resort
    ],
    [
        'category' => 'Chinese',
        'name' => 'Chilli Chicken (Dry / Gravy)',
        'description' => 'Crispy wok-tossed chicken chunks in oriental soy-chilli sauce with fresh bell peppers, scallions, and aromatic spices.',
        'price' => 220,
        'is_veg' => 0,
        'image_path' => 'image/chinese_food.jpg' // Authentic Chilli Chicken dish image
    ],
    [
        'category' => 'Roll, Kebab',
        'name' => 'Kolkata Kathi Rolls & Sizzling Kebabs',
        'description' => 'Crispy flaky paratha rolls filled with tender spiced chicken, served alongside smoky, char-grilled chicken seekh & tikka kebabs.',
        'price' => 220,
        'is_veg' => 0,
        'image_path' => 'image/roll_kabab.jpg' // Authentic Roll & Kebab image
    ]
];
$food_items = $signature_items;
?>

<!--================Breadcrumb Area =================-->
<section class="breadcrumb_area" style="position: relative; overflow: hidden;">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background="" style="background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('image/restaurant_slide_1.jpeg') no-repeat scroll center center / cover; opacity: 1;"></div>
    <div class="container">
        <div class="page-cover text-center">
            <h2 class="page-cover-tittle font-weight-bold text-white">Labanya's Heshel Ghar</h2>
            <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li class="active">Dining</li>
            </ol>
        </div>
    </div>
</section>
<!--================Breadcrumb Area =================-->

<!-- Restaurant Section -->
<section id="restaurant" class="section_gap" style="background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%); position: relative; overflow: hidden;">
    <!-- Subtle Restaurant Background Texture Layer -->
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('image/restaurant_slide_1.jpeg') no-repeat center center / cover; opacity: 0.04; pointer-events: none; z-index: 0;"></div>
    <!-- Decorative background elements for glassmorphism -->
    <div style="position: absolute; top: 5%; left: 5%; width: 300px; height: 300px; background: rgba(255, 193, 7, 0.15); filter: blur(60px); border-radius: 50%; z-index: 0;"></div>
    <div style="position: absolute; bottom: 10%; right: 5%; width: 250px; height: 250px; background: rgba(0, 123, 255, 0.1); filter: blur(50px); border-radius: 50%; z-index: 0;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="section_title text-center mb-4 pt-4">
            <span class="text-uppercase text-warning font-weight-bold text-wrap" style="letter-spacing:1px; white-space: normal; display: inline-block; max-width: 100%;">Multi-Cuisine Dining Experience</span>
            <h2 class="title_color mt-1">Labanya's Heshel Ghar</h2>
            <p class="mx-auto" style="max-width: 750px;">
                Specializing in authentic <strong>Bengali</strong>, delicious <strong>Roll, Kebab</strong>, and wok-fresh <strong>Chinese</strong> delicacies. 
                All meals are prepared fresh upon order with pure local ingredients.
            </p>
        </div>

        <!-- Restaurant Ambience Slider -->
        <div class="row mb-5 justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg rounded-xl overflow-hidden" style="border-radius: 16px;">
                    <div id="restaurantCarousel" class="carousel slide" data-ride="carousel" data-interval="3500">
                        <ol class="carousel-indicators" style="margin-bottom: 20px; z-index: 3;">
                            <li data-target="#restaurantCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#restaurantCarousel" data-slide-to="1"></li>
                            <li data-target="#restaurantCarousel" data-slide-to="2"></li>
                            <li data-target="#restaurantCarousel" data-slide-to="3"></li>
                            <li data-target="#restaurantCarousel" data-slide-to="4"></li>
                            <li data-target="#restaurantCarousel" data-slide-to="5"></li>
                        </ol>
                        <div class="carousel-inner" style="height: 420px; max-height: 55vh;">
                            <div class="carousel-item active h-100">
                                <img src="image/restaurant_slide_1.jpeg" class="d-block w-100 h-100" alt="Restaurant Exterior & Dining Setup" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); border-radius: 8px; padding: 6px 16px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0">Labanya's Heshel Ghar Dining Ambiance</h5>
                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="image/restaurant_slide_2.jpeg" class="d-block w-100 h-100" alt="Spacious Dining Hall" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); border-radius: 8px; padding: 6px 16px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0">Spacious Seating & Family Dining Area</h5>
                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="image/restaurant_slide_3.jpeg" class="d-block w-100 h-100" alt="Restaurant Interior View" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); border-radius: 8px; padding: 6px 16px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0">Warm & Hygienic Dining Environment</h5>
                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="image/restaurant_slide_4.jpeg" class="d-block w-100 h-100" alt="Garden & Restaurant View" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); border-radius: 8px; padding: 6px 16px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0">Scenic Resort Garden Ambiance</h5>
                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="image/restaurant_slide_5.jpeg" class="d-block w-100 h-100" alt="Outdoor & Courtyard Dining" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); border-radius: 8px; padding: 6px 16px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0">Fresh Bolpur Air & Open Courtyard</h5>
                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="image/restaurant_slide_6.jpeg" class="d-block w-100 h-100" alt="Heshel Ghar Hospitality" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); border-radius: 8px; padding: 6px 16px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0">Warm Hospitality & Dedicated Table Service</h5>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#restaurantCarousel" role="button" data-slide="prev" style="width: 12%; z-index: 4;">
                            <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.6); border-radius: 50%; padding: 16px;"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#restaurantCarousel" role="button" data-slide="next" style="width: 12%; z-index: 4;">
                            <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.6); border-radius: 50%; padding: 16px;"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meal Schedule Cards -->
        <div class="row mb-5">
            <!-- Breakfast -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm rounded-lg p-3 text-center border-top border-warning" style="border-top-width: 4px !important; background: rgba(255, 255, 255, 0.45); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);">
                    <i class="fa fa-coffee fa-2x text-warning mb-2"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Breakfast</h5>
                    <div class="badge border text-dark font-weight-bold px-2 py-1 mb-2 font-monospace" style="background: rgba(255,255,255,0.6);">9:00 AM – 10:00 AM</div>
                    <p class="small text-muted mb-0">Luchi-Alur Dom, Puri-Sabji, Toast, Aalu paratha, Eggs &amp; tea & coffee, etc.</p>
                </div>
            </div>

            <!-- Lunch (Thali Service Focus) -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm rounded-lg p-3 text-center border-top border-primary" style="border-top-width: 4px !important; background: rgba(255, 255, 255, 0.45); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);">
                    <i class="fa fa-cutlery fa-2x text-primary mb-2"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Lunch</h5>
                    <div class="badge border text-dark font-weight-bold px-2 py-1 mb-2 font-monospace" style="background: rgba(255,255,255,0.6);">1:00 PM – 3:00 PM</div>
                    <p class="small text-muted mb-0"> Bengali  Thali Service , Prestige Service in the dining hall.</p>
                </div>
            </div>

            <!-- Evening Snacks -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm rounded-lg p-3 text-center border-top border-success" style="border-top-width: 4px !important; background: rgba(255, 255, 255, 0.45); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);">
                    <i class="fa fa-glass fa-2x text-success mb-2"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Evening Snacks</h5>
                    <div class="badge border text-dark font-weight-bold px-2 py-1 mb-2 font-monospace" style="background: rgba(255,255,255,0.6);">6:00 PM – 8:30 PM</div>
                    <p class="small text-muted mb-0">Chilli Chicken, Pakoras, Crispy Baby Corn, Kebab, etc. <br><strong class="text-danger">Last Order: 8:00 PM</strong></p>
                </div>
            </div>

            <!-- Dinner -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm rounded-lg p-3 text-center border-top border-danger" style="border-top-width: 4px !important; background: rgba(255, 255, 255, 0.45); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);">
                    <i class="fa fa-moon-o fa-2x text-danger mb-2"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Dinner</h5>
                    <div class="badge border text-dark font-weight-bold px-2 py-1 mb-2 font-monospace" style="background: rgba(255,255,255,0.6);">9:00 PM – 10:30 PM</div>
                    <p class="small text-muted mb-0">Biryani, Roti, Curries, etc. <br><strong class="text-danger">Last Order: 9:00 PM</strong> | Kitchen Closes: 10:30 PM</p>
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

        <!-- Featured Menu Items (Bengali, Chinese & Roll, Kebab) -->
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
            <?php 
            $food_fallbacks = ['image/food1.jpg', 'image/food2.jpg', 'image/food3.jpg'];
            $f_index = 0;
            foreach ($food_items as $item): 
                $fallback = $food_fallbacks[$f_index % count($food_fallbacks)];
                $f_index++;
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-lg overflow-hidden bg-white" style="transition: transform 0.2s ease;">
                    <?php if(!empty($item['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars($item['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                    <img src="<?php echo $fallback; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div class="p-3 d-flex flex-column h-100">
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
                        
                        <div class="pt-3 border-top mt-auto text-center">
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? ''); ?>?text=Hello,%20I%20would%20like%20to%20enquire%20about%20<?php echo urlencode($item['name']); ?>%20at%20Labanya's%20Heshel%20Ghar." target="_blank" class="btn btn-outline-warning btn-sm btn-block font-weight-bold" style="border-radius: 20px; font-size: 13px; padding: 6px 16px;">
                                <i class="fa fa-whatsapp mr-1 text-success font-weight-bold"></i> Enquire / Order
                            </a>
                        </div>
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

<?php require_once 'includes/footer.php'; ?>
