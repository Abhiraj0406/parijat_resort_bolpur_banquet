<?php
$current_page = "gallery";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch gallery images managed dynamically by admin
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY sort_order ASC, id DESC");
$dynamicGallery = $stmt->fetchAll();

// Fallback if empty
if (empty($dynamicGallery)) {
    $dynamicGallery = [
        ['image_path' => 'image/premium_building_1.jpeg', 'caption' => 'Premium Building Morning View', 'category' => 'Resort'],
        ['image_path' => 'image/premium_building_5.jpeg', 'caption' => 'Premium Building Night Illumination', 'category' => 'Resort'],
        ['image_path' => 'image/gallery/07.jpg', 'caption' => 'Resort Cottages', 'category' => 'Rooms'],
        ['image_path' => 'image/hall1.jpg', 'caption' => 'Banquet Hall Interior', 'category' => 'Events'],
        ['image_path' => 'image/pool3.jpg', 'caption' => 'Night View of Pool', 'category' => 'Pool'],
        ['image_path' => 'image/reception1.jpg', 'caption' => 'Resort Reception', 'category' => 'Resort'],
        ['image_path' => 'image/food2.jpg', 'caption' => 'Authentic Bengali Thali', 'category' => 'Dining'],
        ['image_path' => 'image/restaurant_slide_1.jpeg', 'caption' => 'Labanya\'s Heshel Ghar Dining Area', 'category' => 'Dining'],
        ['image_path' => 'image/restaurant_slide_2.jpeg', 'caption' => 'Restaurant Indoor Seating', 'category' => 'Dining'],
        ['image_path' => 'image/pool5.jpg', 'caption' => 'Illuminated Pool', 'category' => 'Pool'],
        ['image_path' => 'image/hall3.jpg', 'caption' => 'Banquet Hall Decor', 'category' => 'Events']
    ];
}
?>

<!--================Breadcrumb Area =================-->
<section class="breadcrumb_area">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
    <div class="container">
        <div class="page-cover text-center">
            <h2 class="page-cover-tittle font-weight-bold">Photo Gallery</h2>
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Gallery</li>
            </ol>
        </div>
    </div>
</section>
<!--================Breadcrumb Area =================-->

<!-- Gallery Page Scoped Fix -->
<style>
    /* Fix gallery image cards - override global glassmorphism interference */
    .gallery_item .gallery_img {
        background: #000 !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        height: 270px !important;
        position: relative !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: 0 6px 24px rgba(0,0,0,0.4) !important;
    }
    .gallery_item .gallery_img img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        display: block !important;
        border-radius: 0 !important;
        transition: transform 0.4s ease !important;
    }
    .gallery_item .gallery_img:hover img {
        transform: scale(1.07) !important;
    }
    .gallery_item .gallery_img .hover {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, transparent 60%) !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-end !important;
        padding: 16px !important;
        opacity: 0;
        transition: opacity 0.3s ease !important;
        backdrop-filter: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }
    .gallery_item .gallery_img:hover .hover {
        opacity: 1 !important;
    }
    .gallery_item .gallery_img .hover span,
    .gallery_item .gallery_img .hover a,
    .gallery_item .gallery_img .hover i {
        color: #ffffff !important;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.8) !important;
    }
    .gallery_item .gallery_img .hover a.text-warning,
    .gallery_item .gallery_img .hover .text-warning {
        color: #ffc107 !important;
    }
</style>

<!--================Gallery Area =================-->
<section class="gallery_area section_gap bg-light">
    <div class="container">
        <div class="section_title text-center mb-5 pt-5 mt-4">
            <span class="badge badge-pill badge-warning text-dark font-weight-bold px-3 py-2 text-uppercase text-wrap" style="font-size: 0.8rem; letter-spacing: 1px; white-space: normal; max-width: 100%; display: inline-block;">
                <i class="fa fa-camera mr-1"></i> Visual Tour
            </span>
            <h2 class="title_color font-weight-bold mt-2">Moments at Parijat Resort</h2>
            <p class="text-muted">Explore glimpses of our cottage rooms, swimming pool, dining hall and green grounds.</p>
        </div>

        <div class="row imageGallery1" id="gallery">
            <?php foreach ($dynamicGallery as $img): ?>
            <div class="col-lg-4 col-md-6 mb-4 gallery_item">
                <div class="gallery_img" style="height: 270px;">
                    <img src="<?php echo htmlspecialchars($img['image_path']); ?>" alt="<?php echo htmlspecialchars($img['caption'] ?? 'Parijat Resort Gallery'); ?>" loading="lazy" decoding="async">
                    <div class="hover">
                        <?php if (!empty($img['caption'])): ?>
                            <span class="font-weight-bold small mb-1"><?php echo htmlspecialchars($img['caption']); ?></span>
                        <?php endif; ?>
                        <a href="<?php echo htmlspecialchars($img['image_path']); ?>">
                            <i class="fa fa-expand fa-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- WhatsApp Enquiry CTA -->
        <div class="text-center mt-4">
            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site_settings['whatsapp_number'] ?? ''); ?>?text=Hello,%20I%20saw%20the%20resort%20photos%20and%20would%20like%20to%20enquire%20about%20booking." target="_blank" class="btn btn-success px-4 py-2 font-weight-bold rounded-pill shadow-sm">
                <i class="fa fa-whatsapp mr-2"></i> Book Your Stay on WhatsApp
            </a>
        </div>
    </div>
</section>

<?php
include_once 'includes/footer.php';
?>
