<?php
$current_page = "404";
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<!--================404 Error Area =================-->
<section class="section_gap d-flex align-items-center" style="min-height: 70vh;">
    <div class="container text-center">
        <div class="mx-auto p-5 pr-glass-card" style="max-width: 650px;">
            <div class="display-1 font-weight-bold text-warning mb-2" style="font-size: 5rem; letter-spacing: -2px;">404</div>
            <h2 class="title_color font-weight-bold mb-3">Page Not Found</h2>
            <p class="text-muted mb-4" style="font-size: 1.05rem;">
                The page or resource you are looking for may have been relocated, renamed, or is temporarily unavailable.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="./" class="btn theme_btn mr-2">
                    <i class="fa fa-home mr-2"></i>Return Home
                </a>
                <a href="accomodation" class="btn theme_btn_two mr-2">
                    <i class="fa fa-bed mr-2"></i>Explore Rooms
                </a>
                <a href="contact" class="btn btn-outline-dark rounded-pill px-4">
                    <i class="fa fa-envelope-o mr-2"></i>Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
