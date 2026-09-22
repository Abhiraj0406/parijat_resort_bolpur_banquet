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
        ['image_path' => 'image/gallery/07.jpg', 'caption' => 'Resort Cottages', 'category' => 'Rooms'],
        ['image_path' => 'image/gallery/08.jpg', 'caption' => 'Beautiful Resort Courtyard', 'category' => 'Resort'],
        ['image_path' => 'image/hall1.jpg', 'caption' => 'Banquet Hall Interior', 'category' => 'Events'],
        ['image_path' => 'image/pool3.jpg', 'caption' => 'Night View of Pool', 'category' => 'Pool'],
        ['image_path' => 'image/reception1.jpg', 'caption' => 'Resort Reception', 'category' => 'Resort'],
        ['image_path' => 'image/food2.jpg', 'caption' => 'Authentic Bengali Thali', 'category' => 'Dining'],
        ['image_path' => 'image/dining_out.jpg', 'caption' => 'Labanya\'s Heshel Ghar Exterior', 'category' => 'Dining'],
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

<!--================Gallery Area =================-->
<section class="gallery_area section_gap bg-light">
    <div class="container">
        <div class="section_title text-center mb-5">
            <span class="badge badge-pill badge-warning text-dark font-weight-bold px-3 py-1 text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">
                <i class="fa fa-camera mr-1"></i> Visual Tour
            </span>
            <h2 class="title_color font-weight-bold mt-2">Moments at Parijat Resort</h2>
            <p class="text-muted">Explore glimpses of our cottage rooms, swimming pool, dining hall and green grounds.</p>
        </div>

        <div class="row imageGallery1" id="gallery">
            <?php foreach ($dynamicGallery as $img): ?>
            <div class="col-lg-4 col-md-6 mb-4 gallery_item">
                <div class="gallery_img rounded shadow-sm overflow-hidden position-relative bg-white" style="height: 270px;">
                    <img src="<?php echo htmlspecialchars($img['image_path']); ?>" alt="<?php echo htmlspecialchars($img['caption'] ?? 'Parijat Resort Gallery'); ?>" loading="lazy" decoding="async" class="w-100 h-100" style="object-fit: cover; transition: transform 0.4s ease;">
                    <div class="hover d-flex flex-column justify-content-end p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);">
                        <?php if (!empty($img['caption'])): ?>
                            <span class="text-white font-weight-bold small mb-1"><?php echo htmlspecialchars($img['caption']); ?></span>
                        <?php endif; ?>
                        <a class="light text-warning" href="<?php echo htmlspecialchars($img['image_path']); ?>">
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
