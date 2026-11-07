<?php
$page_title = "Room Booking Enquiry - Parijat Resort Bolpur";
$meta_description = "Submit your room reservation enquiry for Parijat Resort Bolpur. All 19 rooms across 4 categories feature private balconies with AC / Non-AC options.";
$og_image = "image/booking_og.jpg";
$current_page = "booking";
include_once 'includes/header.php';

// Fetch Active Categories
$catStmt = $pdo->query("SELECT * FROM room_categories WHERE status='active' ORDER BY id ASC");
$categories = $catStmt->fetchAll();

$preselectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$preselectedAc = isset($_GET['ac']) ? $_GET['ac'] : 'AC';
$preselectedCheckIn = isset($_GET['check_in']) ? htmlspecialchars($_GET['check_in']) : date('Y-m-d');
$preselectedCheckOut = isset($_GET['check_out']) ? htmlspecialchars($_GET['check_out']) : date('Y-m-d', strtotime('+1 day'));
$primaryWhatsApp = $site_settings['whatsapp_number'] ?? '+91 98309 09875';
?>

<!-- Breadcrumb & Hero Pool Carousel Area -->
<section class="breadcrumb_area" style="position: relative; overflow: hidden; min-height: 280px;">
    <div id="bookingHeroCarousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="3500" style="position: absolute; top:0; left:0; width:100%; height:100%; z-index: 0;">
        <ol class="carousel-indicators" style="margin-bottom: 15px; z-index: 3;">
            <li data-target="#bookingHeroCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#bookingHeroCarousel" data-slide-to="1"></li>
            <li data-target="#bookingHeroCarousel" data-slide-to="2"></li>
            <li data-target="#bookingHeroCarousel" data-slide-to="3"></li>
            <li data-target="#bookingHeroCarousel" data-slide-to="4"></li>
            <li data-target="#bookingHeroCarousel" data-slide-to="5"></li>
            <li data-target="#bookingHeroCarousel" data-slide-to="6"></li>
            <li data-target="#bookingHeroCarousel" data-slide-to="7"></li>
        </ol>
        <div class="carousel-inner h-100">
            <div class="carousel-item active h-100">
                <img src="image/pool_slide_1.jpeg" class="d-block w-100 h-100" alt="Swimming Pool" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100">
                <img src="image/pool_slide_2.jpeg" class="d-block w-100 h-100" alt="Swimming Pool Lounge" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100">
                <img src="image/pool_slide_3.jpeg" class="d-block w-100 h-100" alt="Crystal Clear Water" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100">
                <img src="image/pool_slide_4.jpeg" class="d-block w-100 h-100" alt="Poolside Deck" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100">
                <img src="image/pool_slide_5.jpeg" class="d-block w-100 h-100" alt="Lush Green Pool Surroundings" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100">
                <img src="image/pool_slide_6.jpeg" class="d-block w-100 h-100" alt="Resort Pool Ambience" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100">
                <img src="image/pool_slide_7.jpeg" class="d-block w-100 h-100" alt="Swimming Pool Relaxation" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100">
                <img src="image/pool_slide_8.jpeg" class="d-block w-100 h-100" alt="Scenic Pool Area" style="object-fit: cover;">
            </div>
        </div>
        <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)); z-index: 1;"></div>
    </div>
    <div class="container" style="position: relative; z-index: 2;">
        <div class="page-cover text-center pt-3">
            <h2 class="page-cover-tittle font-weight-bold text-white">Room Booking Enquiry</h2>
            <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li><a href="accomodation">Rooms</a></li>
                <li class="active">Booking Enquiry</li>
            </ol>
        </div>
    </div>
</section>

<style>
/* Fix input-group addon icon and input alignment */
.booking-form-box .input-group {
    display: flex !important;
    align-items: stretch !important;
    width: 100% !important;
}
.booking-form-box .input-group-addon {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: rgba(255, 255, 255, 0.15) !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    border-right: none !important;
    padding: 0 15px !important;
    border-top-left-radius: 8px !important;
    border-bottom-left-radius: 8px !important;
    min-width: 48px;
}
.booking-form-box .input-group .form-control {
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
}
.booking-form-box .input-group-addon i {
    font-size: 18px !important;
}

/* Fix dropdown / select box design and options */
.booking-form-box select.form-control {
    display: block !important;
    width: 100% !important;
    height: 48px !important;
    background: rgba(20, 20, 20, 0.7) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    border-radius: 8px !important;
    padding: 10px 15px !important;
    font-size: 15px !important;
    cursor: pointer !important;
    -webkit-appearance: menulist !important;
    -moz-appearance: menulist !important;
    appearance: menulist !important;
}
.booking-form-box select.form-control option {
    background-color: #1a1a1a !important;
    color: #ffffff !important;
    padding: 10px !important;
    font-size: 14px !important;
}

/* Disable problematic nice-select in booking form */
.booking-form-box .nice-select {
    display: none !important;
}
</style>

<div class="container my-5 booking-form-box">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                <div class="card-header bg-dark text-white p-4 text-center">
                    <span class="badge badge-warning text-dark px-3 py-1 font-weight-bold text-uppercase mb-2" style="letter-spacing:1px;">Official Reservation Desk</span>
                    <h3 class="text-white font-weight-bold mb-1">Room Booking &amp; WhatsApp Enquiry</h3>
                    <p class="mb-0 text-white-50 small">
                        Please fill out the details below. Your enquiry will be securely recorded and sent to our reservations team on WhatsApp (<strong class="text-warning"><?php echo htmlspecialchars($primaryWhatsApp); ?></strong>).
                    </p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle mr-2"></i> <?php echo htmlspecialchars($_GET['error']); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <form action="book_process.php" method="POST" class="needs-validation" novalidate id="bookingForm">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <h5 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                            <i class="fa fa-user-circle-o text-warning mr-2"></i> 1. Guest Information
                        </h5>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name" class="font-weight-bold">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter guest's full name" required maxlength="100">
                                <div class="invalid-feedback">Please enter your name.</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="phone" class="font-weight-bold">Mobile Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="background-color: #f8f9fa; border: 1px solid #ced4da; border-right: none; padding: 10px 15px;"><i class="fa fa-phone text-secondary"></i></span>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="10-digit mobile number" required maxlength="20">
                                </div>
                                <div class="invalid-feedback">Please enter a valid calling phone number.</div>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="whatsapp_number" class="font-weight-bold">WhatsApp Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="background-color: #f8f9fa; border: 1px solid #ced4da; border-right: none; padding: 10px 15px;"><i class="fa fa-whatsapp text-success"></i></span>
                                    <input type="tel" class="form-control" id="whatsapp_number" name="whatsapp_number" placeholder="WhatsApp number for booking updates" required maxlength="20">
                                </div>
                                <div class="invalid-feedback">Please enter your WhatsApp number.</div>
                                <small class="form-text text-muted">
                                    <a href="javascript:void(0);" id="sameAsMobile" class="text-primary font-weight-bold">Same as Mobile Number</a>
                                </small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="font-weight-bold">Email Address <span class="text-muted small">(Optional - for email receipt)</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" maxlength="150">
                        </div>

                        <h5 class="font-weight-bold text-dark border-bottom pb-2 mb-3 mt-4">
                            <i class="fa fa-calendar-check-o text-warning mr-2"></i> 2. Stay &amp; Room Details
                        </h5>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="check_in" class="font-weight-bold">Check‑In Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="check_in" name="check_in" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $preselectedCheckIn; ?>" required>
                                <div class="invalid-feedback">Please select check-in date.</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="check_out" class="font-weight-bold">Check‑Out Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="check_out" name="check_out" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" value="<?php echo $preselectedCheckOut; ?>" required>
                                <div class="invalid-feedback">Please select check-out date.</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="category_id" class="font-weight-bold">Room Category <span class="text-danger">*</span></label>
                                <select class="form-control w-100" style="width: 100% !important; display: block; height: auto; padding: 8px 15px;" id="category_id" name="category_id" required>
                                    <option value="" disabled <?php echo !$preselectedCategory ? 'selected' : ''; ?>>-- Select Room Category --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo $preselectedCategory == $cat['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?> (<?php echo $cat['total_rooms']; ?> Rooms)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Please choose a room category.</div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="ac_preference" class="font-weight-bold">AC / Non-AC <span class="text-danger">*</span></label>
                                <select class="form-control w-100" style="width: 100% !important; display: block; height: auto; padding: 8px 15px;" id="ac_preference" name="ac_preference" required>
                                    <option value="AC" <?php echo $preselectedAc === 'AC' ? 'selected' : ''; ?>>AC</option>
                                    <option value="Non-AC" <?php echo $preselectedAc === 'Non-AC' ? 'selected' : ''; ?>>Non-AC</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="number_of_rooms" class="font-weight-bold">No. of Rooms <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="number_of_rooms" name="number_of_rooms" min="1" max="19" value="1" required>
                                <div class="invalid-feedback">Min 1 room required.</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="adults" class="font-weight-bold">Adults (Age 10+) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="adults" name="adults" min="1" max="30" value="2" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kids" class="font-weight-bold">Kids (Under 10 Years)</label>
                                <input type="number" class="form-control" id="kids" name="kids" min="0" max="15" value="0">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="font-weight-bold">Message / Special Requests</label>
                            <textarea class="form-control" id="message" name="message" rows="3" placeholder="Mention any extra bed request, food preferences, early check-in, or occasion..."></textarea>
                        </div>

                        <div class="card bg-light border p-3 mb-4 rounded">
                            <div class="d-flex align-items-start">
                                <i class="fa fa-whatsapp text-success fa-2x mr-3 mt-1"></i>
                                <div>
                                    <strong class="text-dark d-block">Direct WhatsApp Booking Desk</strong>
                                    <p class="small text-muted mb-0">
                                        On clicking the button below, your reservation enquiry will be securely recorded in our system and you will be <strong>directly redirected to WhatsApp</strong> with all your room details pre-filled to send directly to Parijat Resort's reservation executive.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="submitBookingBtn" class="btn btn-success btn-block py-3 font-weight-bold text-uppercase shadow" style="font-size: 1.15rem; letter-spacing: 0.5px;">
                            <i class="fa fa-whatsapp mr-2 fa-lg"></i> Send Enquiry Directly to WhatsApp
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Destroy niceSelect on booking form to allow native select dropdowns
    if (typeof jQuery !== 'undefined') {
        jQuery('.booking-form-box select').each(function() {
            if (jQuery(this).next('.nice-select').length) {
                jQuery(this).next('.nice-select').remove();
            }
            jQuery(this).show();
        });
    }

    // Same as mobile helper
    var sameBtn = document.getElementById('sameAsMobile');
    if (sameBtn) {
        sameBtn.addEventListener('click', function() {
            var phone = document.getElementById('phone').value;
            if (phone) {
                document.getElementById('whatsapp_number').value = phone;
            } else {
                document.getElementById('phone').focus();
            }
        });
    }

    // Dynamic date validation: check_out must be after check_in (timezone-safe)
    var checkInInput = document.getElementById('check_in');
    var checkOutInput = document.getElementById('check_out');

    if (checkInInput && checkOutInput) {
        checkInInput.addEventListener('change', function() {
            if (this.value) {
                var parts = this.value.split('-');
                if (parts.length === 3) {
                    var inDate = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
                    inDate.setDate(inDate.getDate() + 1);
                    var y = inDate.getFullYear();
                    var m = String(inDate.getMonth() + 1).padStart(2, '0');
                    var d = String(inDate.getDate()).padStart(2, '0');
                    var minOut = y + '-' + m + '-' + d;
                    checkOutInput.min = minOut;
                    if (checkOutInput.value && checkOutInput.value <= this.value) {
                        checkOutInput.value = minOut;
                    }
                }
            }
        });
    }

    // Form Bootstrap validation & Submit State
    var form = document.getElementById('bookingForm');
    var submitBtn = document.getElementById('submitBookingBtn');

    if (form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.add('was-validated');
                return;
            }
            form.classList.add('was-validated');

            // Show immediate feedback to user while direct redirecting to WhatsApp
            if (submitBtn) {
                setTimeout(function() {
                    submitBtn.innerHTML = '<i class="fa fa-circle-o-notch fa-spin mr-2"></i> Opening WhatsApp...';
                    submitBtn.style.pointerEvents = 'none';
                    submitBtn.style.opacity = '0.9';
                }, 10);
            }
        }, false);
    }
});
</script>

<?php include_once 'includes/footer.php'; ?>
