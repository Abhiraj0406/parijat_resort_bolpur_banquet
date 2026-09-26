<?php
$page_title = "About Us & Resort Policies - Parijat Resort Bolpur";
$current_page = "about";
$meta_description = "Learn about Parijat Resort Bolpur, our restaurant Labanya's Heshel Ghar, swimming pool rules, and resort policies.";
$og_image = "image/about_bg.jpg";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch policies from DB
$polStmt = $pdo->query("SELECT * FROM policies WHERE status = 'active' ORDER BY id ASC");
$policies = $polStmt->fetchAll();

$poolTimings = !empty($site_settings['swimming_pool_timings']) ? $site_settings['swimming_pool_timings'] : '10:00 AM – 9:30 PM';
$poolNote = !empty($site_settings['swimming_pool_timing_note']) ? $site_settings['swimming_pool_timing_note'] : 'Timings are subject to seasonal schedule and maintenance.';
?>

<!--================Breadcrumb Area =================-->
<section class="breadcrumb_area">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
    <div class="container">
        <div class="page-cover text-center">
            <h2 class="page-cover-tittle">About Us &amp; Policies</h2>
            <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li class="active">About &amp; Guidelines</li>
            </ol>
        </div>
    </div>
</section>
<!--================Breadcrumb Area =================-->

<!--================ About History Area =================-->
<section class="about_history_area section_gap">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 d_flex align-items-center">
                <div class="about_content">
                    <span class="text-uppercase text-warning font-weight-bold" style="letter-spacing:1px;">Tranquil Haven in Shantiniketan</span>
                    <h2 class="title title_color mt-2">Welcome to <br>Parijat Resort Bolpur</h2>
                    <p class="text-muted">
                        Nestled amidst the peaceful, lush green landscape of Bolpur, Parijat Resort is designed for families, couples, and leisure travellers who appreciate tranquil nature alongside modern luxury. With 19 convertible rooms and cottages, a crystal-clear swimming pool, and authentic dining at Labanya's Heshel Ghar, we ensure every stay is unforgettable.
                    </p>
                    <a href="booking" class="btn theme_btn button_hover font-weight-bold px-4 py-2">Make an Enquiry</a>
                </div>
            </div>
            <div class="col-md-6">
                <img class="img-fluid rounded shadow-lg" src="image/about_bg.jpg" alt="Parijat Resort Bolpur Grounds">
            </div>
        </div>
    </div>
</section>

<!--================ Restaurant & Swimming Pool Policies Section =================-->
<section class="section_gap bg-light">
    <div class="container">
        <div class="section_title text-center mb-5">
            <span class="text-uppercase text-warning font-weight-bold" style="letter-spacing:1px;">Guest Guidelines</span>
            <h2 class="title_color mt-1">Dining &amp; Recreation Rules</h2>
            <p>To preserve hygiene, safety, and a restful ambiance for all guests, please take note of our resort guidelines.</p>
        </div>

        <div class="row">
            <!-- Phase 7: Restaurant Labanya's Heshel Ghar -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden bg-white">
                    <div class="card-header bg-dark text-white p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="text-white font-weight-bold mb-0"><i class="fa fa-cutlery text-warning mr-2"></i>Labanya's Heshel Ghar</h4>
                            <span class="badge badge-warning text-dark font-weight-bold text-uppercase">Dining Policies</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">
                            Specializing in authentic <strong>Bengali, Chinese &amp; Roll, Kebab</strong>. All meals are prepared fresh upon order; therefore, <strong>advance meal orders are required</strong>.
                        </p>

                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-clock-o text-warning mr-1"></i> Meal Timings:</h6>
                        <ul class="list-group list-group-flush small mb-3 border rounded">
                            <li class="list-group-item d-flex justify-content-between py-2">
                                <span><strong>Breakfast:</strong></span>
                                <span class="font-monospace">9:00 AM – 10:00 AM</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between py-2">
                                <span><strong>Lunch:</strong> (Authentic Thali Service)</span>
                                <span class="font-monospace">1:00 PM – 3:00 PM</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between py-2">
                                <span><strong>Evening Snacks:</strong> (Last Order: 8:00 PM)</span>
                                <span class="font-monospace">6:00 PM – 8:30 PM</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between py-2">
                                <span><strong>Dinner:</strong> (Last Order: 9:00 PM | Kitchen Closes: 10:30 PM)</span>
                                <span class="font-monospace">9:00 PM – 10:30 PM</span>
                            </li>
                        </ul>

                        <div class="alert alert-warning border-warning small text-dark mb-3">
                            <strong class="d-block"><i class="fa fa-bell text-warning mr-1"></i> Room Delivery Notice:</strong>
                            Room delivery is available for breakfast, evening snacks, and dinner. 
                            <strong>During lunch time room delivery is NOT available because authentic Thali service is exclusively served in the dining hall.</strong>
                        </div>

                        <div class="alert alert-danger border-danger small text-danger mb-0">
                            <strong><i class="fa fa-ban mr-1"></i> Outside Food Policy:</strong>
                            Outside food and beverages are strictly NOT permitted inside resort premises or guest rooms.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phase 8: Swimming Pool Rules & Timings -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden bg-white">
                    <div class="card-header bg-primary text-white p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="text-white font-weight-bold mb-0"><i class="fa fa-life-ring text-white mr-2"></i>Swimming Pool Regulations</h4>
                            <span class="badge badge-light text-primary font-weight-bold text-uppercase">Pool Rules</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="p-3 mb-3 bg-light border-left border-primary rounded" style="border-left-width: 4px !important;">
                            <span class="text-muted small d-block font-weight-bold text-uppercase">Configured Pool Operating Hours</span>
                            <strong class="text-dark" style="font-size: 1.25rem;">
                                <i class="fa fa-clock-o text-primary mr-2"></i> <?php echo htmlspecialchars($poolTimings); ?>
                            </strong>
                            <small class="text-muted d-block mt-1"><?php echo htmlspecialchars($poolNote); ?></small>
                        </div>

                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-check-square-o text-success mr-1"></i> Mandatory Pool Guidelines:</h6>
                        <ul class="list-unstyled small mb-3">
                            <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> <strong>Swimwear:</strong> Guests must bring their own swimwear.</li>
                            <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> <strong>Material Preference:</strong> <em>Synthetic swimwear is strictly preferred</em> for pool filtration and hygiene maintenance.</li>
                            <li class="mb-2"><i class="fa fa-shower text-primary mr-2"></i> Mandatory shower before stepping into pool water.</li>
                            <li class="mb-2"><i class="fa fa-child text-warning mr-2"></i> Children must be accompanied by parents or guardians at all times.</li>
                        </ul>

                        <div class="alert alert-warning border-warning small text-dark mb-0">
                            <h6 class="font-weight-bold text-dark mb-1"><i class="fa fa-exclamation-triangle text-warning mr-1"></i> Special Holi Festival Regulation:</h6>
                            During Holi, guests should strictly <strong>NOT</strong> enter the pool while playing with colours. All colours must be completely washed off with fresh water before using the pool.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include_once 'includes/footer.php';
?>
