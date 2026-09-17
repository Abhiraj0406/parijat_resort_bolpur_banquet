<?php
$current_page = "rules";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch dynamic site settings
$phone = $site_settings['phone_primary'] ?? '+91 98309 09875';
$whatsapp = $site_settings['whatsapp_number'] ?? '+91 98309 09875';
$clean_wa = preg_replace('/[^0-9]/', '', $whatsapp);
?>

<!-- Breadcrumb Area -->
<section class="breadcrumb_area">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
    <div class="container">
        <div class="page-cover text-center">
            <h2 class="page-cover-tittle font-weight-bold">Rules &amp; Regulations</h2>
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Resort Policies &amp; Guest Guidelines</li>
            </ol>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="section_gap bg-light">
    <div class="container">

        <!-- Introduction Banner -->
        <div class="text-center mb-5">
            <div class="badge badge-pill badge-warning text-dark font-weight-bold px-3 py-1 text-uppercase mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">
                <i class="fa fa-shield mr-1"></i> Guest Information &amp; Code of Conduct
            </div>
            <h2 class="title_color font-weight-bold">Resort Guidelines &amp; Policies</h2>
            <p class="text-muted mx-auto" style="max-width: 720px; font-size: 1.05rem;">
                To ensure a peaceful, hygienic, and memorable stay for all guests at Parijat Resort, please familiarize yourself with our resort regulations.
            </p>
        </div>

        <!-- 1. Check-in & Check-out (Rule 1 & 2) -->
        <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                <i class="fa fa-clock-o text-primary fa-lg mr-2"></i>
                <h5 class="font-weight-bold text-dark mb-0">1. Check-In &amp; Check-Out Schedule</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="p-3 bg-light rounded border-left border-primary" style="border-left-width: 4px !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted font-weight-bold text-uppercase small">Standard Check-In</span>
                                <span class="badge badge-primary px-2 py-1 font-monospace" style="font-size: 0.95rem;">12:30 PM</span>
                            </div>
                            <p class="small text-muted mb-2">
                                Rooms are ready for occupancy from 12:30 PM onwards upon presentation of valid government photo ID.
                            </p>
                            <!-- IMPORTANT CLIENT NOTICE BADGE -->
                            <div class="alert alert-warning py-1 px-2 small mb-0 rounded" style="font-size: 0.82rem;">
                                <i class="fa fa-info-circle mr-1 text-warning"></i>
                                <strong>Advisory Note:</strong> Standard hospitality check-in is <strong>12:30 PM</strong>. 
                                (Earlier client communication referenced 12:30 AM; kindly confirm with front office prior to arrival if late night check-in is required).
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border-left border-dark" style="border-left-width: 4px !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted font-weight-bold text-uppercase small">Standard Check-Out</span>
                                <span class="badge badge-dark px-2 py-1 font-monospace" style="font-size: 0.95rem;">11:00 AM</span>
                            </div>
                            <p class="small text-muted mb-0">
                                Check-out is strictly at 11:00 AM to allow our housekeeping team adequate sanitization time for arriving guests. Late checkout requests are subject to availability and nominal charges.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Restaurant, Meals & Food Policy (Rule 3, 4, 17, 18) -->
        <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                <i class="fa fa-cutlery text-warning fa-lg mr-2"></i>
                <h5 class="font-weight-bold text-dark mb-0">2. Dining, Restaurant Timings &amp; Food Policies</h5>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="border rounded p-3 h-100 bg-white">
                            <strong class="text-dark d-block mb-1"><i class="fa fa-coffee text-warning mr-1"></i> Breakfast</strong>
                            <div class="badge badge-light border text-dark font-monospace mb-2">9:00 AM – 10:00 AM</div>
                            <div class="small text-muted">Freshly prepared morning breakfast served hot.</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="border rounded p-3 h-100 bg-white">
                            <strong class="text-dark d-block mb-1"><i class="fa fa-cutlery text-primary mr-1"></i> Lunch (Thali)</strong>
                            <div class="badge badge-light border text-dark font-monospace mb-2">1:00 PM – 3:00 PM</div>
                            <div class="small text-muted">Authentic Bengali &amp; multi-cuisine Thali service.</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="border rounded p-3 h-100 bg-white">
                            <strong class="text-dark d-block mb-1"><i class="fa fa-glass text-success mr-1"></i> Snacks</strong>
                            <div class="badge badge-light border text-dark font-monospace mb-2">6:00 PM – 8:30 PM</div>
                            <div class="small text-danger font-weight-bold">Last order: 8:00 PM</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="border rounded p-3 h-100 bg-white">
                            <strong class="text-dark d-block mb-1"><i class="fa fa-moon-o text-danger mr-1"></i> Dinner</strong>
                            <div class="badge badge-light border text-dark font-monospace mb-2">9:00 PM – 10:30 PM</div>
                            <div class="small text-danger font-weight-bold">Last order: 9:00 PM | Closes: 10:30 PM</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Outside Food -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="p-3 bg-light rounded border-left border-danger" style="border-left-width: 4px !important;">
                            <h6 class="font-weight-bold text-danger mb-1"><i class="fa fa-ban mr-1"></i> Outside Food is NOT Allowed</h6>
                            <p class="small text-muted mb-0">
                                To ensure hygiene, kitchen quality standards, and pest prevention, outside cooked food or food deliveries from third-party vendors are strictly prohibited on the resort premises.
                            </p>
                        </div>
                    </div>

                    <!-- Room Delivery & Thali Rule -->
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border-left border-warning" style="border-left-width: 4px !important;">
                            <h6 class="font-weight-bold text-dark mb-1"><i class="fa fa-bell mr-1"></i> Room Delivery Service Policy</h6>
                            <p class="small text-muted mb-0">
                                Room delivery is gladly provided for breakfast, evening snacks, and dinner. 
                                <strong class="text-dark d-block mt-1">Special Note: Room delivery is NOT available during lunch hours because authentic Thali service is exclusively served in the dining hall.</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Booking, Advance & Cancellation Policies (Rule 5, 6, 7, 8) -->
        <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                <i class="fa fa-credit-card text-success fa-lg mr-2"></i>
                <h5 class="font-weight-bold text-dark mb-0">3. Booking Deposit, Cancellation &amp; Rescheduling</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="badge badge-success p-2 mr-3 rounded-circle"><i class="fa fa-check"></i></div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">50% Advance Booking Deposit</h6>
                                <p class="small text-muted mb-0">
                                    All room bookings require a mandatory <strong>50% advance payment</strong> to confirm the reservation. The remaining 50% must be cleared during check-in.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="badge badge-danger p-2 mr-3 rounded-circle"><i class="fa fa-times"></i></div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Strict No-Refund Cancellation Policy</h6>
                                <p class="small text-muted mb-0">
                                    In case of reservation cancellation, <strong>no monetary refund</strong> will be issued under any circumstances.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="d-flex align-items-start">
                            <div class="badge badge-primary p-2 mr-3 rounded-circle"><i class="fa fa-calendar"></i></div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">3-Month Date Carry Forward (Regular Season)</h6>
                                <p class="small text-muted mb-0">
                                    During regular periods, cancelled bookings may be carried forward to an alternate date within <strong>3 months</strong> from the original cancellation date (subject to room availability).
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="badge badge-warning p-2 mr-3 rounded-circle text-dark"><i class="fa fa-exclamation"></i></div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Peak Season Special Rules</h6>
                                <p class="small text-muted mb-0">
                                    During peak tourist seasons, festivals (e.g., Poush Mela, Basanta Utsav) and long weekends:
                                    <span class="d-block mt-1 font-weight-bold text-danger">&bull; No refund &bull; No date alteration</span>
                                    <span class="small text-muted">Date alteration is considered <em>only if</em> a replacement booking is received for the exact same dates.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Occupancy, Extra Person & Visitors (Rule 9 & 10) -->
        <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                <i class="fa fa-users text-info fa-lg mr-2"></i>
                <h5 class="font-weight-bold text-dark mb-0">4. Guest Occupancy, Extra Person &amp; Visitors</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="p-3 bg-light rounded border-left border-info" style="border-left-width: 4px !important;">
                            <h6 class="font-weight-bold text-dark mb-1"><i class="fa fa-user-plus text-info mr-1"></i> Extra Person Policy (Ages 10+)</h6>
                            <p class="small text-muted mb-0">
                                An extra person aged <strong>10 years and above is chargeable</strong> with additional mattress and linen provided upon request. Children under 10 years stay complimentary when sharing existing bedding.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border-left border-secondary" style="border-left-width: 4px !important;">
                            <h6 class="font-weight-bold text-dark mb-1"><i class="fa fa-user-times text-secondary mr-1"></i> Visitors Policy (9:30 PM Cutoff)</h6>
                            <p class="small text-muted mb-0">
                                Relatives and external visitors are warmly welcome to visit guests in common areas. However, for guest safety and privacy, <strong>visitors are strictly not allowed after 9:30 PM</strong> in room premises.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Swimming Pool Guidelines (Rule 11, 12, 13) -->
        <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                <i class="fa fa-tint text-primary fa-lg mr-2"></i>
                <h5 class="font-weight-bold text-dark mb-0">5. Swimming Pool Guidelines</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-lg-4 mb-3 mb-lg-0">
                        <div class="p-3 bg-light rounded h-100 border-top border-primary" style="border-top-width: 3px !important;">
                            <strong class="text-dark d-block mb-1"><i class="fa fa-clock-o text-primary mr-1"></i> Pool Operational Hours</strong>
                            <div class="badge badge-primary font-monospace px-2 py-1 mb-2">10:00 AM – 9:30 PM</div>
                            <p class="small text-muted mb-0">
                                The swimming pool is open daily. Night swimming after 9:30 PM is strictly prohibited for filtration, chlorination and safety purposes.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3 mb-lg-0">
                        <div class="p-3 bg-light rounded h-100 border-top border-info" style="border-top-width: 3px !important;">
                            <strong class="text-dark d-block mb-1"><i class="fa fa-life-ring text-info mr-1"></i> Mandatory Swimwear</strong>
                            <p class="small text-muted mb-0">
                                Guests must wear proper synthetic/nylon swimming costumes. Cotton clothes, denims, or casual t-shirts are not permitted inside pool water.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="p-3 bg-light rounded h-100 border-top border-danger" style="border-top-width: 3px !important;">
                            <strong class="text-dark d-block mb-1"><i class="fa fa-paint-brush text-danger mr-1"></i> Holi &amp; Colors Policy</strong>
                            <p class="small text-muted mb-0">
                                To protect filtration pumps and water hygiene, entering the pool with colors/gulal is strictly prohibited. Guests must shower thoroughly before entering the pool.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Housekeeping, Room Kits & Sanitization (Rule 14, 15, 16) -->
        <div class="card border-0 shadow-sm rounded-lg mb-5 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                <i class="fa fa-bed text-success fa-lg mr-2"></i>
                <h5 class="font-weight-bold text-dark mb-0">6. Housekeeping &amp; Room Amenities</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="p-3 bg-light rounded border-left border-success" style="border-left-width: 4px !important;">
                            <h6 class="font-weight-bold text-dark mb-1"><i class="fa fa-calendar-check-o text-success mr-1"></i> Housekeeping Timings: 10:00 AM – 11:00 AM</h6>
                            <p class="small text-muted mb-0">
                                Daily room cleaning and bed making is performed between <strong>10:00 AM and 11:00 AM</strong> only. 
                                <strong class="text-danger d-block mt-1">Please note: After 11:00 AM, daily room cleaning service will not be available.</strong>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border-left border-primary" style="border-left-width: 4px !important;">
                            <h6 class="font-weight-bold text-dark mb-1"><i class="fa fa-gift text-primary mr-1"></i> Multiple-Night Room Kit Refill</h6>
                            <p class="small text-muted mb-0">
                                For guests staying multiple consecutive nights, complimentary bathroom kits (soaps, shampoo sachets, dental kits) and tea/coffee supplies are refilled upon daily morning housekeeping requests.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Assistance Helpdesk Card -->
        <div class="card border-0 shadow-sm rounded-lg text-center p-4 bg-white">
            <h5 class="font-weight-bold text-dark mb-2">Have Questions or Need Special Assistance?</h5>
            <p class="text-muted mb-3">Our front desk team is available 24/7 to assist with your inquiries, special requests, and bookings.</p>
            <div>
                <a href="tel:<?php echo htmlspecialchars($phone); ?>" class="btn theme_btn mr-2 mb-2">
                    <i class="fa fa-phone mr-2"></i>Call <?php echo htmlspecialchars($phone); ?>
                </a>
                <a href="https://wa.me/<?php echo $clean_wa; ?>?text=Hello,%20I%20have%20a%20query%20regarding%20Parijat%20Resort%20rules%20and%20booking%20policies." target="_blank" class="btn btn-success px-4 py-2 font-weight-bold mb-2 shadow-sm rounded-pill">
                    <i class="fa fa-whatsapp mr-2"></i>Chat on WhatsApp
                </a>
            </div>
        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
