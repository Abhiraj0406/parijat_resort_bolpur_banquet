<?php
$current_page = "contact";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Site & Management Details
$resort_name = $site_settings['resort_name'] ?? 'Parijat Resort Bolpur';
$address = $site_settings['address'] ?? 'Near Shantiniketan, Bolpur, Birbhum, West Bengal - 731204, India';
$phone_primary = $site_settings['phone_primary'] ?? '+91 98302 76364';
$phone_secondary = $site_settings['phone_secondary'] ?? '+91 98309 09875';
$whatsapp = $site_settings['whatsapp_number'] ?? '+91 98309 09875';
$email = $site_settings['email_address'] ?? 'info@parijatresortbolpur.com';

// Management Team
$proprietor = $site_settings['proprietor_name'] ?? 'Mr. Ananda Kumar Saha';
$finance_head = $site_settings['finance_head_name'] ?? 'Mr. Arindam Saha';
$manager = $site_settings['manager_name'] ?? 'Mr. Tonmay Guha';
$manager_phone = $site_settings['manager_phone'] ?? '+91 87775 13554';

$clean_wa = preg_replace('/[^0-9]/', '', $whatsapp);
$clean_p1 = preg_replace('/[^0-9]/', '', $phone_primary);
$clean_p2 = preg_replace('/[^0-9]/', '', $phone_secondary);
$clean_mgr = preg_replace('/[^0-9]/', '', $manager_phone);
?>

<!--================Breadcrumb Area =================-->
<section class="breadcrumb_area" style="position: relative; overflow: hidden;">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background="" style="background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('image/entrance_slide_1.jpeg') no-repeat scroll center center / cover; opacity: 1;"></div>
    <div class="container">
        <div class="page-cover text-center">
            <h2 class="page-cover-tittle font-weight-bold text-white">Management &amp; Contact</h2>
            <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li class="active">Contact Us</li>
            </ol>
        </div>
    </div>
</section>
<!--================Breadcrumb Area =================-->

<!--================ Contact & Management Area =================-->
<section class="contact_area section_gap bg-light">
    <div class="container">

        <!-- Management Team Section (Phase 11) -->
        <div class="mb-5">
            <div class="text-center mb-4 pt-5 mt-4">
                <span class="badge badge-pill badge-warning text-dark font-weight-bold px-3 py-2 text-uppercase text-wrap" style="font-size: 0.82rem; letter-spacing: 1px; white-space: normal; max-width: 100%; display: inline-block;">
                    <i class="fa fa-users mr-1"></i> Resort Leadership
                </span>
                <h2 class="title_color font-weight-bold mt-2">Executive Management</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Dedicated leadership committed to warm hospitality and exceptional guest experiences at Parijat Resort Bolpur.
                </p>
            </div>

            <div class="row">
                <!-- Proprietor -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm rounded-lg p-4 text-center h-100 bg-white border-top border-warning" style="border-top-width: 4px !important;">
                        <div class="mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="fa fa-user-circle text-warning fa-3x"></i>
                        </div>
                        <h5 class="font-weight-bold text-dark mb-1"><?php echo htmlspecialchars($proprietor); ?></h5>
                        <div class="text-warning font-weight-bold text-uppercase small mb-3">Proprietor</div>
                        <p class="small text-muted mb-0">
                            Guiding vision and stewardship of Parijat Resort, fostering genuine cultural warmth and serene leisure near Shantiniketan.
                        </p>
                    </div>
                </div>

                <!-- Finance & Admin Head -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm rounded-lg p-4 text-center h-100 bg-white border-top border-primary" style="border-top-width: 4px !important;">
                        <div class="mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="fa fa-briefcase text-primary fa-2x"></i>
                        </div>
                        <h5 class="font-weight-bold text-dark mb-1"><?php echo htmlspecialchars($finance_head); ?></h5>
                        <div class="text-primary font-weight-bold text-uppercase small mb-3">Finance &amp; Administrative Head</div>
                        <p class="small text-muted mb-0">
                            Overseeing institutional administration, guest services excellence, and corporate accounts.
                        </p>
                    </div>
                </div>

                <!-- Resort Manager -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="card border-0 shadow-sm rounded-lg p-4 text-center h-100 bg-white border-top border-success" style="border-top-width: 4px !important;">
                        <div class="mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="fa fa-id-badge text-success fa-2x"></i>
                        </div>
                        <h5 class="font-weight-bold text-dark mb-1"><?php echo htmlspecialchars($manager); ?></h5>
                        <div class="text-success font-weight-bold text-uppercase small mb-2">Resort Manager</div>
                        <p class="small text-muted mb-3">
                            Direct operations manager overseeing on-site guest check-ins, banquet coordination, and room services.
                        </p>
                        <!-- Click to Call Manager -->
                        <a href="tel:<?php echo $clean_mgr; ?>" class="btn btn-outline-success btn-sm rounded-pill font-weight-bold px-3">
                            <i class="fa fa-phone mr-1"></i> Call: <?php echo htmlspecialchars($manager_phone); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Direct Booking, Calling & WhatsApp Hub (Phase 11 Click-to-Call & Click-to-WhatsApp) -->
        <div class="card border-0 shadow rounded-lg p-4 mb-5 bg-white">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <span class="badge badge-success px-3 py-1 font-weight-bold text-uppercase mb-2">Instant Booking &amp; Enquiries</span>
                    <h3 class="font-weight-bold text-dark mb-2">Get in Touch Directly</h3>
                    <p class="text-muted mb-4">
                        Speak directly with our reservation team for room bookings, group packages, and banquet hall reservations.
                    </p>

                    <div class="d-flex flex-wrap gap-2">
                        <!-- Click-to-Call Line 1 -->
                        <a href="tel:<?php echo $clean_p1; ?>" class="btn btn-primary px-4 py-2 font-weight-bold shadow-sm rounded-pill mr-2 mb-2">
                            <i class="fa fa-phone mr-2"></i> Call: <?php echo htmlspecialchars($phone_primary); ?>
                        </a>

                        <!-- Click-to-Call Line 2 -->
                        <a href="tel:<?php echo $clean_p2; ?>" class="btn btn-outline-primary px-4 py-2 font-weight-bold rounded-pill mr-2 mb-2">
                            <i class="fa fa-phone mr-2"></i> Call: <?php echo htmlspecialchars($phone_secondary); ?>
                        </a>

                        <!-- Click-to-WhatsApp -->
                        <a href="https://wa.me/<?php echo $clean_wa; ?>?text=Hello,%20I%20would%20like%20to%20enquire%20about%20booking%20at%20Parijat%20Resort%20Bolpur." target="_blank" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm rounded-pill mb-2">
                            <i class="fa fa-whatsapp mr-2"></i> WhatsApp: <?php echo htmlspecialchars($whatsapp); ?>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="p-3 bg-light rounded border">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="fa fa-map-marker text-danger mr-2"></i> Resort Location &amp; Hours</h6>
                        <p class="text-muted small mb-2">
                            <strong>Address:</strong> <?php echo htmlspecialchars($address); ?>
                        </p>
                        <p class="text-muted small mb-2">
                            <strong>Front Office:</strong> Open 24/7 for assisted check-in &amp; guest support.
                        </p>
                        <p class="text-muted small mb-0">
                            <strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($email); ?>"><?php echo htmlspecialchars($email); ?></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form & Direct Messages -->
        <div class="row">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="card border-0 shadow-sm rounded-lg p-4 bg-white h-100">
                    <h5 class="font-weight-bold text-dark mb-3"><i class="fa fa-info-circle text-primary mr-2"></i> Reservation Notes</h5>
                    <ul class="list-unstyled text-muted small leading-loose mb-0">
                        <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> <strong>Check-In:</strong> 12:30 PM (Confirm if late arrival)</li>
                        <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> <strong>Check-Out:</strong> 11:00 AM</li>
                        <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> <strong>Advance:</strong> 50% deposit required for confirmed reservations</li>
                        <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> <strong>Pool Hours:</strong> 10:00 AM – 9:30 PM</li>
                        <li><i class="fa fa-check text-success mr-2"></i> <strong>Outside Food:</strong> Strictly not allowed</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-lg p-4 bg-white">
                    <h5 class="font-weight-bold text-dark mb-3">Send Us a Direct Message</h5>
                    <form class="contact_form" action="contact_process.php" method="post" id="contactForm">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <input type="hidden" name="type" value="general_contact">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-dark">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Your name" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-dark">Phone / WhatsApp <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+91..." required>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-dark">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-dark">Subject / Event Type</label>
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Room Enquiry / Banquet / Dining">
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-dark">Message / Travel Dates</label>
                                    <textarea class="form-control" name="message" id="message" rows="4" placeholder="Tell us your requirements, expected guests, or dates..." required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <button type="submit" value="submit" class="btn btn-success font-weight-bold px-4 py-2 shadow-sm rounded-pill" style="background-color: #25d366 !important; border-color: #25d366 !important; color: #fff !important;">
                                    <i class="fa fa-whatsapp mr-1 font-weight-bold" style="font-size: 1.1rem;"></i> Send Message on WhatsApp
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>

<?php
include_once 'includes/footer.php';
?>
