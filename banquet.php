<?php
$page_title = "Banquet & Conference Hall - Parijat Resort Bolpur";
$current_page = "banquet";
$meta_description = "Host your weddings, corporate seminars, and family get-togethers at the Grand Parijat Banquet & Conference Hall.";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch Banquet Details
$banquetStmt = $pdo->query("SELECT * FROM banquet_details LIMIT 1");
$banquet = $banquetStmt->fetch();

// Hardcoded full set of 11 authentic Banquet Hall photos
$banquet_images = [
    ['image_path' => 'image/banquet_slide_1.jpeg', 'alt_text' => 'Grand Banquet Hall Front View'],
    ['image_path' => 'image/banquet_slide_2.jpeg', 'alt_text' => 'AC Conference & Banquet Setup'],
    ['image_path' => 'image/banquet_slide_3.jpeg', 'alt_text' => 'Banquet Seating Arrangement'],
    ['image_path' => 'image/banquet_slide_4.jpeg', 'alt_text' => 'Spacious Event Stage & Hall'],
    ['image_path' => 'image/banquet_slide_5.jpeg', 'alt_text' => 'Banquet Lighting & Decor'],
    ['image_path' => 'image/banquet_slide_6.jpeg', 'alt_text' => 'Conference Hall Table Setup'],
    ['image_path' => 'image/banquet_slide_7.jpeg', 'alt_text' => 'Event Reception & Hall Interior'],
    ['image_path' => 'image/banquet_slide_8.jpeg', 'alt_text' => 'Grand Celebration Hall Space'],
    ['image_path' => 'image/banquet_slide_9.jpeg', 'alt_text' => 'Banquet Hall Dining & Buffet Area'],
    ['image_path' => 'image/banquet_slide_10.jpeg', 'alt_text' => 'Fully AC Conference Facilities'],
    ['image_path' => 'image/banquet_slide_11.jpeg', 'alt_text' => 'Premium Banquet Hall Atmosphere']
];
?>

<!--================Breadcrumb Area =================-->
<section class="breadcrumb_area" style="position: relative; overflow: hidden;">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background="" style="background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('image/banquet_slide_1.jpeg') no-repeat scroll center center / cover; opacity: 1;"></div>
    <div class="container">
        <div class="page-cover text-center">
            <h2 class="page-cover-tittle font-weight-bold text-white">Grand Banquet &amp; Conference Hall</h2>
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Banquet</li>
            </ol>
        </div>
    </div>
</section>
<!--================Breadcrumb Area =================-->

<!-- Banquet / Conference Hall Section -->
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

                    <!-- Capacity & Pricing Info -->
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
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-check text-success mr-1"></i> Hall Facilities &amp; Amenities:</h6>
                        <div class="row">
                            <?php 
                            $fac_list = ['AC', 'Sound System', 'Projector Screen', 'Stage Lighting', 'Dedicated Catering by Labanya Heshel Ghar', 'Sprawling Lawn'];
                            foreach($fac_list as $fac_trim): 
                            ?>
                                <div class="col-sm-6 mb-2">
                                    <div class="small text-muted d-flex align-items-center">
                                        <i class="fa fa-dot-circle-o text-warning mr-2"></i>
                                        <?php echo htmlspecialchars($fac_trim); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-3 mt-3">
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? ''); ?>?text=Hello,%20I%20would%20like%20to%20enquire%20about%20booking%20the%20Banquet/Conference%20Hall%20at%20Parijat%20Resort." target="_blank" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm rounded-pill mr-3 mb-2">
                            <i class="fa fa-whatsapp mr-2"></i> WhatsApp Enquiry
                        </a>
                        <a href="#banquet-booking" class="button_hover theme_btn_two mb-2" style="border-radius: 30px; padding: 10px 25px;"><i class="fa fa-calendar-check-o mr-1"></i> Book Banquet</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative mt-4 mt-lg-0">
                    <?php if (!empty($banquet_images)): ?>
                    <div id="banquetCarousel" class="carousel slide shadow-lg rounded" data-ride="carousel" data-interval="3500">
                        <ol class="carousel-indicators" style="margin-bottom: 25px; z-index: 3;">
                            <?php foreach ($banquet_images as $index => $img): ?>
                            <li data-target="#banquetCarousel" data-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>"></li>
                            <?php endforeach; ?>
                        </ol>
                        <div class="carousel-inner rounded">
                            <?php foreach ($banquet_images as $index => $img): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img class="d-block w-100" src="<?php echo htmlspecialchars($img['image_path']); ?>" alt="<?php echo htmlspecialchars($img['alt_text'] ?? 'Banquet Image'); ?>" style="height: 500px; object-fit: cover;">
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($banquet_images) > 1): ?>
                        <a class="carousel-control-prev" href="#banquetCarousel" role="button" data-slide="prev" style="width: 12%; z-index: 4;">
                            <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.6); border-radius: 50%; padding: 16px;"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#banquetCarousel" role="button" data-slide="next" style="width: 12%; z-index: 4;">
                            <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.6); border-radius: 50%; padding: 16px;"></span>
                            <span class="sr-only">Next</span>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <img class="img-fluid rounded shadow-lg w-100" src="image/gallery/02.jpg" alt="Banquet Hall" style="height: 500px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="position-absolute p-3 rounded shadow-sm bg-white" style="bottom: 20px; left: 20px; max-width: 280px; opacity: 0.95;">
                        <h6 class="font-weight-bold text-dark mb-1"><i class="fa fa-glass text-warning mr-1"></i> Tailored Event Catering</h6>
                        <small class="text-muted">Direct catering support from Labanya's Heshel Ghar available for all events.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Banquet Booking Enquiry Form Section -->
<section id="banquet-booking" class="section_gap" style="background-color: #f9f9ff;">
    <div class="container">
        <div class="section_title text-center mb-5">
            <h2 class="title_color">Book Our Banquet / Event Hall</h2>
            <p>Please fill out the form below and our events team will get back to you with the best packages.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-body p-4 p-md-5">
                        <form action="contact_process.php" method="POST" id="banquetForm">
                            <input type="hidden" name="subject" value="Banquet Booking Enquiry">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" required placeholder="Enter full name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="phone" required placeholder="10-digit mobile number">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Event Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="event_date" required min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Event Type <span class="text-danger">*</span></label>
                                    <select class="form-control w-100" style="display: block; width: 100% !important; padding: 8px 15px; height: auto;" name="event_type" required>
                                        <option value="">Select Event Type</option>
                                        <option value="Wedding / Reception">Wedding / Reception</option>
                                        <option value="Corporate Event / Seminar">Corporate Event / Seminar</option>
                                        <option value="Birthday / Anniversary">Birthday / Anniversary</option>
                                        <option value="Get Together / Party">Get Together / Party</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Expected Guests <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="guests" required min="10" placeholder="Number of guests">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Catering Required? <span class="text-danger">*</span></label>
                                    <select class="form-control w-100" style="display: block; width: 100% !important; padding: 8px 15px; height: auto;" name="catering" required>
                                        <option value="Yes">Yes, require food & catering</option>
                                        <option value="No">No, only hall required</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Additional Requirements</label>
                                <textarea class="form-control" name="message" rows="4" placeholder="Any special requests, decoration needs, room bookings, etc..."></textarea>
                            </div>
                            <button type="submit" class="btn theme_btn button_hover w-100 py-3 font-weight-bold text-uppercase mt-2 text-wrap" style="border-radius: 5px; white-space: normal; line-height: 1.5;">
                                <i class="fa fa-paper-plane mr-2"></i> Submit Booking Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php else: ?>
<section class="section_gap text-center py-5">
    <div class="container py-5">
        <h3>Banquet information is currently being updated. Please check back later.</h3>
    </div>
</section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
