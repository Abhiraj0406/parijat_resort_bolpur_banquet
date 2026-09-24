<?php
$page_title = "Rooms & Cottages Tariff - Parijat Resort Bolpur";
$current_page = "accomodation";
$meta_description = "Explore all 19 rooms and cottages across 4 premium categories at Parijat Resort Bolpur. All rooms offer private balconies and AC/Non-AC options.";
$og_image = "image/room1.jpg";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch Active Room Categories with images and rates
$stmt = $pdo->query("SELECT c.*, 
        (SELECT image_path FROM room_images WHERE category_id = c.id AND status = 'active' ORDER BY sort_order ASC, id ASC LIMIT 1) as custom_image,
        (SELECT rate_ac FROM room_rates WHERE category_id = c.id AND (rate_ac > 0 OR rate_non_ac > 0) ORDER BY rate_type ASC, id DESC LIMIT 1) as rate_ac,
        (SELECT rate_non_ac FROM room_rates WHERE category_id = c.id AND (rate_ac > 0 OR rate_non_ac > 0) ORDER BY rate_type ASC, id DESC LIMIT 1) as rate_non_ac,
        (SELECT rate_title FROM room_rates WHERE category_id = c.id AND (rate_ac > 0 OR rate_non_ac > 0) ORDER BY rate_type ASC, id DESC LIMIT 1) as rate_title
        FROM room_categories c 
        WHERE c.status = 'active' 
        ORDER BY c.id ASC");
$rooms = $stmt->fetchAll();

// Fetch seasonal and weekend rates for transparency table
$ratesStmt = $pdo->query("SELECT r.*, c.name as category_name 
                          FROM room_rates r 
                          JOIN room_categories c ON r.category_id = c.id 
                          WHERE c.status = 'active' 
                          ORDER BY c.id ASC, r.rate_type ASC");
$allRates = $ratesStmt->fetchAll();
?>

<!--================Breadcrumb Area =================-->
<section class="breadcrumb_area" style="position: relative; overflow: hidden;">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background="" style="background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('image/premium_building_1.jpeg') no-repeat scroll center center / cover; opacity: 1;"></div>
    <div class="container">
        <div class="page-cover text-center">
            <h2 class="page-cover-tittle font-weight-bold text-white">Resort Accommodation</h2>
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Rooms & Cottages</li>
            </ol>
        </div>
    </div>
</section>
<!--================Breadcrumb Area =================-->

<!--================ Detailed Accomodation Area =================-->
<section class="accomodation_area section_gap">
    <div class="container">
        <!-- Premium Building Architecture (Morning & Night Views) -->
        <div class="row mb-5 justify-content-center">
            <div class="col-lg-11">
                <div class="card border-0 shadow-lg rounded-xl overflow-hidden" style="border-radius: 16px;">
                    <div id="premiumBuildingCarousel" class="carousel slide" data-ride="carousel" data-interval="3500">
                        <ol class="carousel-indicators" style="margin-bottom: 20px; z-index: 3;">
                            <li data-target="#premiumBuildingCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#premiumBuildingCarousel" data-slide-to="1"></li>
                            <li data-target="#premiumBuildingCarousel" data-slide-to="2"></li>
                            <li data-target="#premiumBuildingCarousel" data-slide-to="3"></li>
                            <li data-target="#premiumBuildingCarousel" data-slide-to="4"></li>
                            <li data-target="#premiumBuildingCarousel" data-slide-to="5"></li>
                        </ol>
                        <div class="carousel-inner" style="height: 420px; max-height: 55vh;">
                            <div class="carousel-item active h-100">
                                <img src="image/premium_building_1.jpeg" class="d-block w-100 h-100" alt="Premium Building Morning Architecture" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.65); border-radius: 8px; padding: 8px 18px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0"><i class="fa fa-sun-o text-warning mr-2"></i>Premium Accommodation Building (Morning View)</h5>
                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="image/premium_building_2.jpeg" class="d-block w-100 h-100" alt="Premium Building Daylight View" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.65); border-radius: 8px; padding: 8px 18px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0"><i class="fa fa-building text-info mr-2"></i>Spacious Balconies & Garden Facade</h5>
                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="image/premium_building_3.jpeg" class="d-block w-100 h-100" alt="Premium Building Afternoon Ambience" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.65); border-radius: 8px; padding: 8px 18px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0"><i class="fa fa-tree text-success mr-2"></i>Serene Greenery & Scenic Grounds</h5>
                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="image/premium_building_4.jpeg" class="d-block w-100 h-100" alt="Premium Building Dusk Lighting" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.65); border-radius: 8px; padding: 8px 18px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0"><i class="fa fa-moon-o text-warning mr-2"></i>Illuminated Evening Facade</h5>
                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="image/premium_building_5.jpeg" class="d-block w-100 h-100" alt="Premium Building Night View" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.65); border-radius: 8px; padding: 8px 18px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0"><i class="fa fa-star text-warning mr-2"></i>Premium Building Night Ambience & Lighting</h5>
                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="image/premium_building_6.jpeg" class="d-block w-100 h-100" alt="Peaceful Night Atmosphere" style="object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.65); border-radius: 8px; padding: 8px 18px; bottom: 25px;">
                                    <h5 class="text-white font-weight-bold mb-0"><i class="fa fa-bed text-white mr-2"></i>Peaceful & Secure Night Atmosphere</h5>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#premiumBuildingCarousel" role="button" data-slide="prev" style="width: 12%; z-index: 4;">
                            <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.6); border-radius: 50%; padding: 16px;"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#premiumBuildingCarousel" role="button" data-slide="next" style="width: 12%; z-index: 4;">
                            <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.6); border-radius: 50%; padding: 16px;"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="section_title text-center pt-2 mt-2">
            <span class="text-uppercase text-warning font-weight-bold text-wrap" style="letter-spacing: 1px; white-space: normal; display: inline-block; max-width: 100%;">19 Serene Units</span>
            <h2 class="title_color mt-1">Our Room Categories</h2>
            <p>Every room at Parijat Resort Bolpur is convertible to <strong>AC or Non-AC</strong> and includes a <strong>private balcony</strong> with serene views.</p>
        </div>

        <div class="row">
            <?php 
            if (count($rooms) == 1) {
                $rooms[] = $rooms[0];
            }

            $demoImages = ['image/room1.jpg', 'image/room2.jpg', 'image/room3.jpg', 'image/room4.jpg', 'image/room5.jpg', 'image/room6.jpg'];
            $roomIndex = 0;
            foreach ($rooms as $room): 
                $imgSrc = !empty($room['custom_image']) ? htmlspecialchars($room['custom_image']) : $demoImages[$roomIndex % 6];
                $roomIndex++;
                $facilitiesList = !empty($room['facilities']) ? explode(',', $room['facilities']) : [];
            ?>
            <div class="col-lg-6 col-md-12 mb-5">
                <div class="card h-100 border-0 shadow rounded-lg overflow-hidden" style="transition: all 0.3s ease;">
                    <div class="position-relative" style="height: 280px; overflow: hidden;">
                        <?php if ($room['id'] == 1): ?>
                            <!-- Image Slider for Deluxe Cottage Room -->
                            <div id="roomCarousel1" class="carousel slide h-100 w-100" data-ride="carousel" data-interval="4000">
                                <ol class="carousel-indicators" style="margin-bottom: 35px; z-index: 3;">
                                    <li data-target="#roomCarousel1" data-slide-to="0" class="active"></li>
                                    <li data-target="#roomCarousel1" data-slide-to="1"></li>
                                    <li data-target="#roomCarousel1" data-slide-to="2"></li>
                                </ol>
                                <div class="carousel-inner h-100">
                                    <div class="carousel-item active h-100">
                                        <img src="image/cottage_slide1.jpg" alt="Deluxe Cottage Room" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/cottage_slide2.jpg" alt="Deluxe Cottage Room Side View" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/cottage_slide3.jpg" alt="Deluxe Cottage Bathroom with Geyser" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#roomCarousel1" role="button" data-slide="prev" style="width: 15%; z-index: 4;">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 14px;"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#roomCarousel1" role="button" data-slide="next" style="width: 15%; z-index: 4;">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 14px;"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        <?php elseif ($room['id'] == 2): ?>
                            <!-- Image Slider for Deluxe Premium Ground Floor -->
                            <div id="roomCarousel2" class="carousel slide h-100 w-100" data-ride="carousel" data-interval="4000">
                                <ol class="carousel-indicators" style="margin-bottom: 35px; z-index: 3;">
                                    <li data-target="#roomCarousel2" data-slide-to="0" class="active"></li>
                                    <li data-target="#roomCarousel2" data-slide-to="1"></li>
                                    <li data-target="#roomCarousel2" data-slide-to="2"></li>
                                    <li data-target="#roomCarousel2" data-slide-to="3"></li>
                                    <li data-target="#roomCarousel2" data-slide-to="4"></li>
                                    <li data-target="#roomCarousel2" data-slide-to="5"></li>
                                    <li data-target="#roomCarousel2" data-slide-to="6"></li>
                                    <li data-target="#roomCarousel2" data-slide-to="7"></li>
                                    <li data-target="#roomCarousel2" data-slide-to="8"></li>
                                </ol>
                                <div class="carousel-inner h-100">
                                    <div class="carousel-item active h-100">
                                        <img src="image/ground_slide_1.jpeg" alt="Deluxe Premium Ground Floor Room" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/ground_slide_2.jpeg" alt="Deluxe Premium Ground Floor Bed View" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/ground_slide_3.jpeg" alt="Deluxe Premium Ground Floor Balcony & View" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/ground_slide_4.jpeg" alt="Modern Bathroom Amenities" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/ground_slide_5.jpeg" alt="Deluxe Premium Ground Floor Interior" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/ground_slide_6.jpeg" alt="Deluxe Premium Ground Floor Bathroom" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/ground_slide_7.jpeg" alt="Deluxe Premium Ground Floor Room Setup" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/ground_slide_8.jpeg" alt="Deluxe Premium Ground Floor Wash Area" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/ground_slide_9.jpeg" alt="Deluxe Premium Ground Floor Comfort" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#roomCarousel2" role="button" data-slide="prev" style="width: 15%; z-index: 4;">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 14px;"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#roomCarousel2" role="button" data-slide="next" style="width: 15%; z-index: 4;">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 14px;"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        <?php elseif ($room['id'] == 3): ?>
                            <!-- Image Slider for Deluxe Premium First Floor -->
                            <div id="roomCarousel3" class="carousel slide h-100 w-100" data-ride="carousel" data-interval="4000">
                                <ol class="carousel-indicators" style="margin-bottom: 35px; z-index: 3;">
                                    <li data-target="#roomCarousel3" data-slide-to="0" class="active"></li>
                                    <li data-target="#roomCarousel3" data-slide-to="1"></li>
                                    <li data-target="#roomCarousel3" data-slide-to="2"></li>
                                    <li data-target="#roomCarousel3" data-slide-to="3"></li>
                                    <li data-target="#roomCarousel3" data-slide-to="4"></li>
                                    <li data-target="#roomCarousel3" data-slide-to="5"></li>
                                    <li data-target="#roomCarousel3" data-slide-to="6"></li>
                                    <li data-target="#roomCarousel3" data-slide-to="7"></li>
                                    <li data-target="#roomCarousel3" data-slide-to="8"></li>
                                </ol>
                                <div class="carousel-inner h-100">
                                    <div class="carousel-item active h-100">
                                        <img src="image/first_slide_1.jpeg" alt="Deluxe Premium First Floor Room" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/first_slide_2.jpeg" alt="Deluxe Premium First Floor Bed View" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/first_slide_3.jpeg" alt="Deluxe Premium First Floor Interior" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/first_slide_4.jpeg" alt="Deluxe Premium First Floor Balcony & View" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/first_slide_5.jpeg" alt="Modern Bathroom Amenities" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/first_slide_6.jpeg" alt="Deluxe Premium First Floor Room Setup" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/first_slide_7.jpeg" alt="Deluxe Premium First Floor Bathroom" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/first_slide_8.jpeg" alt="Deluxe Premium First Floor Wash Area" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/first_slide_9.jpeg" alt="Deluxe Premium First Floor Comfort" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#roomCarousel3" role="button" data-slide="prev" style="width: 15%; z-index: 4;">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 14px;"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#roomCarousel3" role="button" data-slide="next" style="width: 15%; z-index: 4;">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 14px;"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        <?php elseif ($room['id'] == 4): ?>
                            <!-- Image Slider for Family Premium Four Sharing -->
                            <div id="roomCarousel4" class="carousel slide h-100 w-100" data-ride="carousel" data-interval="4000">
                                <ol class="carousel-indicators" style="margin-bottom: 35px; z-index: 3;">
                                    <li data-target="#roomCarousel4" data-slide-to="0" class="active"></li>
                                    <li data-target="#roomCarousel4" data-slide-to="1"></li>
                                    <li data-target="#roomCarousel4" data-slide-to="2"></li>
                                    <li data-target="#roomCarousel4" data-slide-to="3"></li>
                                </ol>
                                <div class="carousel-inner h-100">
                                    <div class="carousel-item active h-100">
                                        <img src="image/family_slide1.jpg" alt="Family Premium Suite Double Bed View" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/family_slide2.jpg" alt="Family Suite Two Beds Layout" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/family_slide3.jpg" alt="Family Suite Wardrobe and TV View" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="carousel-item h-100">
                                        <img src="image/family_slide4.jpg" alt="Modern Bathroom with Geyser" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#roomCarousel4" role="button" data-slide="prev" style="width: 15%; z-index: 4;">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 14px;"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#roomCarousel4" role="button" data-slide="next" style="width: 15%; z-index: 4;">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 14px;"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        <?php else: ?>
                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" class="w-100 h-100" style="object-fit: cover;">
                        <?php endif; ?>
                        
                        <div class="position-absolute" style="top: 15px; left: 15px; display: flex; flex-direction: column; z-index: 5;">
                            <div class="mb-2">
                                <span class="badge badge-dark px-3 py-2 shadow font-weight-bold" style="font-size: 12px;">
                                    <i class="fa fa-key mr-1"></i> <?php echo $room['total_rooms']; ?> Rooms Available
                                </span>
                            </div>

                            <?php if (!empty($room['has_balcony'])): ?>
                            <div>
                                <span class="badge badge-success px-3 py-2 shadow font-weight-bold" style="font-size: 12px;">
                                    <i class="fa fa-sun-o mr-1"></i> Private Balcony
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="position-absolute" style="bottom: 12px; left: 15px; z-index: 5;">
                            <span class="badge badge-warning text-dark px-3 py-2 shadow font-weight-bold" style="font-size: 12px;">
                                <i class="fa fa-snowflake-o mr-1"></i> Convertible: AC or Non-AC
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="font-weight-bold text-dark mb-2" style="font-size: 1.35rem;">
                                <?php echo htmlspecialchars($room['name']); ?>
                            </h3>
                            
                            <p class="text-muted mb-4" style="line-height: 1.6;">
                                <?php echo htmlspecialchars($room['description']); ?>
                            </p>

                            <!-- Specifications Grid -->
                            <div class="bg-light p-3 rounded mb-4">
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-users text-warning mr-2" style="font-size: 18px;"></i>
                                            <div>
                                                <small class="text-muted d-block">Occupancy</small>
                                                <strong><?php echo $room['adult_capacity']; ?> Adults, <?php echo $room['kids_capacity']; ?> Kids</strong>
                                                <span class="small text-muted">(Max <?php echo $room['max_occupancy']; ?>)</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-bed text-warning mr-2" style="font-size: 18px;"></i>
                                            <div>
                                                <small class="text-muted d-block">Extra Person</small>
                                                <strong>Available on Request</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-sun-o text-warning mr-2" style="font-size: 18px;"></i>
                                            <div>
                                                <small class="text-muted d-block">Balcony</small>
                                                <strong><?php echo $room['has_balcony'] ? 'Attached Private Balcony' : 'Standard View'; ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-sliders text-warning mr-2" style="font-size: 18px;"></i>
                                            <div>
                                                <small class="text-muted d-block">Flexibility</small>
                                                <strong>Convertible</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Room Facilities -->
                            <?php if (!empty($facilitiesList)): ?>
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-dark mb-2">Room Amenities &amp; Facilities:</h6>
                                <div class="d-flex flex-wrap" style="gap: 6px;">
                                    <?php foreach ($facilitiesList as $fac): ?>
                                        <span class="badge badge-light border text-dark py-2 px-3" style="font-size: 12px; font-weight: 500;">
                                            <i class="fa fa-check text-success mr-1"></i> <?php echo trim(htmlspecialchars($fac)); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Price Box & Action Buttons -->
                            <div class="pt-3 border-top mt-auto">
                                <div class="mb-3">
                                    <div class="text-warning font-weight-bold" style="font-size: 1.1rem;">
                                        <i class="fa fa-tag mr-1"></i> Tariff on Request
                                    </div>
                                    <small class="text-muted d-block">Contact us for best rates</small>
                                </div>

                                <div class="row m-0 w-100">
                                    <div class="col-6 pl-0 pr-1">
                                        <a href="booking.php?category=<?php echo $room['id']; ?>" class="btn btn-sm btn-dark w-100 text-white py-2 rounded-pill font-weight-bold shadow-sm text-center">
                                            Book Online
                                        </a>
                                    </div>
                                    <div class="col-6 pr-0 pl-1">
                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? ''); ?>?text=Hello,%20I%20am%20enquiring%20about%20<?php echo urlencode($room['name']); ?>%20at%20Parijat%20Resort." target="_blank" class="btn btn-sm btn-success w-100 text-white py-2 rounded-pill font-weight-bold shadow-sm text-center" title="Enquire on WhatsApp">
                                            <i class="fa fa-whatsapp"></i> WhatsApp
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Tariff & Booking Policy Note -->
        <div class="card bg-transparent border-light shadow-sm rounded-lg p-4 mt-4 mb-5" style="background: rgba(0, 0, 0, 0.6) !important; backdrop-filter: blur(10px);">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h5 class="font-weight-bold text-white mb-2"><i class="fa fa-info-circle text-warning mr-2"></i>Flexible Tariff Policy</h5>
                    <p class="text-white mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                        Prices vary according to high season (Durga Puja, Winter holidays, Shantiniketan festivals) and long weekends. All 19 rooms are convertible and can be booked with AC or Non-AC based on your preference and budget. To check real-time availability and custom group discounts, please submit an enquiry or contact our reservations desk.
                    </p>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <a href="index.php#contact" class="btn btn-dark font-weight-bold btn-block rounded-pill py-2 mb-2 shadow-sm">Online Reservation</a>
                    <a href="contact.php" class="btn btn-outline-light font-weight-bold btn-block rounded-pill py-2 shadow-sm">Contact Front Desk</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ Detailed Accomodation Area =================-->

<?php
include_once 'includes/footer.php';
?>
