<?php
// includes/header.php
require_once __DIR__ . '/config.php';

// Default page names
if (!isset($current_page)) {
    $current_page = "home";
}

// Fetch site settings
$stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
$site_settings = [];
while ($row = $stmt->fetch()) {
    $site_settings[$row['setting_key']] = $row['setting_value'];
}
$resort_name = $site_settings['resort_name'] ?? 'Parijat Resort Bolpur';

// Fetch SEO settings for the current page
$seoStmt = $pdo->prepare("SELECT * FROM seo_settings WHERE page_name = :page");
$seoStmt->execute([':page' => $current_page]);
$seo = $seoStmt->fetch();

$page_title = $seo['meta_title'] ?? ($resort_name . ' - ' . ucfirst($current_page));
$meta_description = $seo['meta_description'] ?? 'Welcome to ' . $resort_name;
$meta_keywords = $seo['meta_keywords'] ?? '';
$canonical_url = $seo['canonical_url'] ?? 'https://parijatresortbolpur.com/';
$og_title = $seo['og_title'] ?? $page_title;
$og_description = $seo['og_description'] ?? $meta_description;
$og_image = $seo['og_image'] ?? 'image/logo-parijat.png';
?>
<!doctype html>
<html lang="en" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="image/favicon.png" type="image/png">
        
        <title><?php echo htmlspecialchars($page_title); ?></title>
        <meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>">
        <?php if (!empty($meta_keywords)): ?>
        <meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords); ?>">
        <?php endif; ?>
        
        <meta property="og:title" content="<?php echo htmlspecialchars($og_title); ?>" />
        <meta property="og:description" content="<?php echo htmlspecialchars($og_description); ?>" />
        <meta property="og:image" content="<?php echo htmlspecialchars($og_image); ?>" />
        <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>" />

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="css/bootstrap.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="vendors/linericon/style.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="vendors/owl-carousel/owl.carousel.min.css">
        <link rel="stylesheet" href="vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="vendors/nice-select/css/nice-select.css">
        
        <!-- Dark Mode CSS Logic -->
        <style>
        html[data-theme="dark"] {
            filter: invert(1) hue-rotate(180deg);
            background-color: #111; /* Ensures smooth background color flip */
        }
        
        /* Re-invert images, videos, backgrounds, and icons so they look normal in dark mode */
        html[data-theme="dark"] img,
        html[data-theme="dark"] video,
        html[data-theme="dark"] .bg-parallax,
        html[data-theme="dark"] .gallery_img,
        html[data-theme="dark"] .card-img-top,
        html[data-theme="dark"] i.fa,
        html[data-theme="dark"] i.bi,
        html[data-theme="dark"] .logo_h {
            filter: invert(1) hue-rotate(180deg);
        }

        /* Adjust some theme colors that get washed out */
        html[data-theme="dark"] .btn-warning,
        html[data-theme="dark"] .badge-warning,
        html[data-theme="dark"] .text-warning {
            filter: invert(1) hue-rotate(180deg) brightness(1.2);
        }
        
        /* Ensure navbar doesn't glitch */
        html[data-theme="dark"] .header_area {
            background: #fff; /* will be inverted to dark */
        }
        </style>

        <!-- JSON-LD Structured Data for LocalBusiness / Hotel (Accurate data without invented ratings) -->
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": ["Hotel", "Resort"],
          "name": "<?php echo addslashes($resort_name); ?>",
          "url": "https://parijatresortbolpur.com/",
          "logo": "https://parijatresortbolpur.com/image/logo-parijat.png",
          "image": [
            "https://parijatresortbolpur.com/image/room1.jpg",
            "https://parijatresortbolpur.com/image/room2.jpg"
          ],
          "description": "<?php echo addslashes($meta_description); ?>",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "Near Shantiniketan",
            "addressLocality": "Bolpur",
            "addressRegion": "West Bengal",
            "postalCode": "731204",
            "addressCountry": "IN"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": 23.6708,
            "longitude": 87.7197
          },
          "telephone": "<?php echo htmlspecialchars($site_settings['phone_primary'] ?? '+91 98302 76364'); ?>",
          "checkinTime": "12:30",
          "checkoutTime": "11:00",
          "numberOfRooms": 19,
          "amenityFeature": [
            { "@type": "LocationFeatureSpecification", "name": "Swimming Pool", "value": true },
            { "@type": "LocationFeatureSpecification", "name": "Restaurant - Labanya's Heshel Ghar", "value": true },
            { "@type": "LocationFeatureSpecification", "name": "Private Balconies", "value": true },
            { "@type": "LocationFeatureSpecification", "name": "Banquet and Conference Hall", "value": true },
            { "@type": "LocationFeatureSpecification", "name": "Convertible AC and Non-AC Rooms", "value": true }
          ]
        }
        </script>

        <!-- AOS Animation CSS -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        
        <!-- Main CSS -->
        <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="css/responsive.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <!--================Header Area =================-->
        <header class="header_area">
            <div class="container-fluid" style="padding: 0;">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <!-- Brand -->
                    <a class="navbar-brand logo_h" href="index.php">
                        <img src="image/logo-parijat.png?v=3" alt="<?php echo htmlspecialchars($resort_name); ?>" style="max-height: 80px; width: auto; max-width: 100%; transform: scale(1.25); transform-origin: left center; margin-left: 10px;">
                    </a>
                    
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    
                    <!-- Navbar Links -->
                    <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                        <ul class="nav navbar-nav menu_nav ml-auto">
                            <li class="nav-item <?php echo ($current_page == 'index') ? 'active' : ''; ?>"><a class="nav-link" href="index.php">Home</a></li> 
                            <li class="nav-item <?php echo ($current_page == 'accomodation') ? 'active' : ''; ?>"><a class="nav-link" href="accomodation.php">Rooms</a></li>
                            <li class="nav-item <?php echo ($current_page == 'restaurant') ? 'active' : ''; ?>"><a class="nav-link" href="dining.php">Dining</a></li>
                            <li class="nav-item <?php echo ($current_page == 'banquet') ? 'active' : ''; ?>"><a class="nav-link" href="banquet.php">Banquet</a></li>
                            <li class="nav-item <?php echo ($current_page == 'rules') ? 'active' : ''; ?>"><a class="nav-link" href="rules.php">Rules & Policies</a></li>
                            <li class="nav-item <?php echo ($current_page == 'gallery') ? 'active' : ''; ?>"><a class="nav-link" href="gallery.php">Gallery</a></li>
                            <li class="nav-item <?php echo ($current_page == 'contact') ? 'active' : ''; ?>"><a class="nav-link" href="contact.php">Contact</a></li>
                        </ul>
                    </div> 
                </nav>
            </div>
        </header>
        <!--================Header Area =================-->
