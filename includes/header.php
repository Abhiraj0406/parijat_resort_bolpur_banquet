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
        <meta name="google-site-verification" content="j0RniskA1nUFWcsL7bLQddixF84uvNrJCppyD6Lq9Eo" />
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
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="css/bootstrap.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="vendors/linericon/style.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="vendors/owl-carousel/owl.carousel.min.css">
        <link rel="stylesheet" href="vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.css">
        <!-- Flatpickr Modern Calendar -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
        <link rel="stylesheet" href="vendors/nice-select/css/nice-select.css">
        
        <!-- Frontend Overrides & Dark Mode -->
        <style>
        /* Modern Premium Font */
        body, h1, h2, h3, h4, h5, h6, p, a, div, span, li, button, input {
            font-family: 'Outfit', 'Poppins', sans-serif !important;
        }
        
        /* Convert Blue Theme to Resort Green Theme */
        .text-primary, .text-info {
            color: #2e7d32 !important; /* Premium Emerald Green */
        }
        .bg-primary, .bg-info {
            background-color: #2e7d32 !important;
        }
        .border-primary, .border-info {
            border-color: #2e7d32 !important;
        }
        .btn-primary, .btn-info {
            background-color: #2e7d32 !important;
            border-color: #2e7d32 !important;
            color: #fff !important;
        }
        .btn-outline-primary, .btn-outline-info {
            color: #2e7d32 !important;
            border-color: #2e7d32 !important;
        }
        .btn-outline-primary:hover, .btn-outline-info:hover {
            background-color: #2e7d32 !important;
            color: #fff !important;
        }
        
        /* Increase text size for better readability */
        body, p, ul li, ol li, .form-control, label {
            font-size: 16px !important;
            line-height: 1.8 !important;
        }
        .small, small, .text-muted {
            font-size: 15px !important;
            line-height: 1.7 !important;
        }
        
        /* Global Mobile Responsiveness Fixes */
        html, body {
            overflow-x: hidden !important;
            width: 100%;
            max-width: 100vw;
        }
        
        /* Footer Glassmorphism & Text Visibility */
        .footer-area {
            position: relative;
            background: url('image/pool3.jpg') no-repeat center center / cover !important;
            color: #fff !important;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .footer-area::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(6, 78, 59, 0.85); /* Dark Emerald Green Overlay */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 0;
        }
        .footer-area .container {
            position: relative;
            z-index: 1;
        }
        .footer-area h6, .footer-area p, .footer-area a, .footer-area i, .footer-area li {
            color: rgba(255, 255, 255, 0.95) !important;
        }
        .footer-area .footer_title {
            color: #fff !important;
            font-weight: 700;
            border-bottom: 2px solid rgba(16, 185, 129, 0.5);
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .footer-area a:hover {
            color: #2e7d32 !important; /* Premium Emerald Hover */
        }
        
        /* Mobile Menu Hamburger Visibility Fix */
        .navbar-light .navbar-toggler {
            border-color: #2e7d32 !important;
            background-color: #2e7d32 !important;
        }
        .navbar-light .navbar-toggler .icon-bar {
            background-color: #fff !important;
        }
        
        /* High Clarity Hero Banner Styling */
        .banner_area {
            background: #111 !important;
        }
        .banner_area .bg-parallax {
            background: url('image/banner_bg.jpg?v=<?php echo file_exists('image/banner_bg.jpg') ? filemtime('image/banner_bg.jpg') : 3; ?>') no-repeat center center / cover !important;
            opacity: 0.90 !important;
            background-attachment: scroll !important;
            transform: none !important;
        }
        .banner_content h2 {
            text-shadow: 0 3px 18px rgba(0, 0, 0, 0.85), 0 1px 4px rgba(0, 0, 0, 0.95) !important;
        }
        .banner_content h6, .banner_content p {
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.9) !important;
        }
        
        /* Resort Facilities Area Pool Background */
        .facilities_area .bg-parallax {
            background: linear-gradient(rgba(4, 9, 30, 0.65), rgba(4, 9, 30, 0.65)), url('image/pool_bg.jpg') no-repeat center center / cover !important;
            opacity: 1 !important;
        }
        
        @media (max-width: 768px) {
            h1, .h1 { font-size: 32px !important; line-height: 1.3 !important; }
            h2, .h2, .banner_content h2 { font-size: 28px !important; line-height: 1.3 !important; }
            h3, .h3 { font-size: 24px !important; }
            h4, .h4 { font-size: 20px !important; }
            
            /* Fix banner overlapping and scrolling */
            .banner_content {
                padding: 0 15px;
                word-wrap: break-word;
            }
            
            /* Fix banner background showing wrong image and zooming bug on mobile */
            .banner_area .bg-parallax {
                background: url('image/banner_bg.jpg?v=<?php echo file_exists('image/banner_bg.jpg') ? filemtime('image/banner_bg.jpg') : 3; ?>') no-repeat center center / cover !important;
                background-attachment: scroll !important;
                transform: none !important;
                opacity: 0.90 !important;
            }
            
            /* Fix booking form overlapping on mobile */
            .hotel_booking_area {
                position: relative !important;
                margin-top: 20px !important;
                padding-bottom: 20px !important;
            }
            
            /* Make text smaller on mobile if it was bumped up too much */
            body, p, ul li, ol li, .form-control, label {
                font-size: 15px !important;
            }
            
            /* Ensure images don't break grid */
            img {
                max-width: 100%;
                height: auto;
            }
        }
        
        /* ==================================================
           GLOBAL GLASSMORPHISM THEME
           ================================================== */
        <?php
        // Dynamic background image selection according to the current page
        $bg_image = 'image/gallery/01.jpg'; // Default: Resort/Room
        $page_key = strtolower($current_page ?? 'home');
        
        switch ($page_key) {
            case 'restaurant':
            case 'dining':
            case 'food':
                $bg_image = 'image/food2.jpg'; // Authentic Food / Bengali Dining Feast
                break;
            case 'banquet':
            case 'events':
            case 'hall':
                $bg_image = 'image/hall1.jpg'; // Grand Banquet Hall Interior
                break;
            case 'accomodation':
            case 'accommodation':
            case 'rooms':
            case 'booking':
            case 'book':
                $bg_image = 'image/gallery/01.jpg'; // Luxury Room & Bed
                break;
            case 'about':
            case 'about-us':
                $bg_image = 'image/gallery/08.jpg'; // Resort Courtyard & Architecture
                break;
            case 'gallery':
                $bg_image = 'image/gallery/09.jpg'; // Evening Resort Ambience
                break;
            case 'rules':
            case 'policies':
                $bg_image = 'image/gallery/07.jpg'; // Resort Cottages
                break;
            case 'contact':
            case 'contact-us':
                $bg_image = 'image/reception_lobby_1.jpeg'; // Resort Reception & Front Desk
                break;
            default:
                $bg_image = 'image/gallery/01.jpg';
                break;
        }
        ?>
        body { 
            background: url('<?php echo htmlspecialchars($bg_image); ?>') no-repeat center center fixed !important; 
            background-size: cover !important; 
        } 
        /* Add a dark overlay over the background image but behind the content */ 
        body::before { 
            content: ''; 
            position: fixed; 
            top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0, 0, 0, 0.75); 
            z-index: -1; 
        } 
         
        /* Make all section backgrounds transparent so the body background shows through */ 
        .section_gap, .bg-light, .bg-white, .bg-dark, .accomodation_area, .about_history_area, .facilities_area, .gallery_area, .testimonial_area, .glass_section, .contact-area, .breadcrumb_area, #restaurant { 
            background: transparent !important; 
            background-color: transparent !important; 
        } 
        .breadcrumb_area {
            display: none !important;
        } 
         
        /* Convert all white cards and containers to glass cards */
        .card, .facilities_item, .about_content, .contact_info {
            background: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(15px) !important;
            -webkit-backdrop-filter: blur(15px) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2) !important;
            border-radius: 16px !important;
            transition: transform 0.3s ease, background 0.3s ease;
            height: auto !important;
            overflow: visible !important;
            padding: 30px !important;
        }
        
        /* Ensure floats are cleared inside glass containers */
        .card::after, .about_content::after, .contact_info::after {
            content: "";
            display: table;
            clear: both;
        } 
         
        .card:hover, .facilities_item:hover { 
            background: rgba(255, 255, 255, 0.12) !important; 
        } 
         
        /* Convert text colors to white for readability on dark background */ 
        h1, h2, h3, h4, h5, h6, p, span, div, label, 
        .text-dark, .text-muted, .title_color, .sec_h4 { 
            color: #ffffff !important; 
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5); 
        } 
         
        /* Preserve specific colors and keep buttons looking normal */ 
        .btn, .btn span, .btn i, 
        .badge, .badge span, .badge i, 
        i.fa, i.bi { 
            text-shadow: none !important; 
        } 
         
        .btn.btn-dark { 
            background-color: rgba(0,0,0,0.7) !important; 
            border-color: rgba(255,255,255,0.3) !important; 
            color: #fff !important; 
        }
        .btn.btn-success { 
            background-color: #2e7d32 !important; 
            border-color: #2e7d32 !important; 
            color: #fff !important; 
        } 
        .text-warning, .fa-star, .fa-tag { 
            color: #2e7d32 !important; 
        } 
        .text-success, .fa-leaf, .fa-whatsapp, .fa-check { 
            color: #2e7d32 !important; 
        } 
        
        /* Fix tags, badges, alerts, and inputs to match glass theme */ 
        .badge-warning { background-color: rgba(243, 195, 0, 0.9) !important; color: #000 !important; border: none !important; } 
        .badge-light, .badge-white, .alert-warning { background-color: rgba(255,255,255,0.15) !important; color: #fff !important; border: 1px solid rgba(255,255,255,0.2) !important; text-shadow: none !important; } 
        .badge-dark { background-color: rgba(0,0,0,0.6) !important; color: #fff !important; } 
        .badge-success { background-color: rgba(16, 185, 129, 0.8) !important; color: #fff !important; } 
        
        .form-control, input, textarea, select {
            background: rgba(255,255,255,0.1) !important;
            border: 1px solid rgba(255,255,255,0.3) !important;
            color: #fff !important;
            border-radius: 8px !important;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.6) !important; }
        .form-control:focus { background: rgba(255,255,255,0.15) !important; border-color: #2e7d32 !important; }
        
        .bg-light, .bg-white { background-color: rgba(255,255,255,0.05) !important; }
         
        /* Ensure Header remains completely solid and readable */ 
        .header_area { background: #ffffff !important; } 
        .header_area h1, .header_area h2, .header_area h3, .header_area h4, .header_area h5, .header_area h6,  
        .header_area p, .header_area span, .header_area div, .header_area a, .header_area .nav-link { 
            text-shadow: none !important;
        } 
        .header_area .nav-link, .header_area .logo_h { color: #222222 !important; } 
        .header_area .nav-item.active .nav-link, .header_area .nav-link:hover { color: #2e7d32 !important; }
        
        /* Stop glassmorphism on the footer since it has its own beautiful design now */
        .footer-area h1, .footer-area h2, .footer-area h3, .footer-area h4, .footer-area h5, .footer-area h6,  
        .footer-area p, .footer-area span, .footer-area div, .footer-area a {
            text-shadow: none !important;
        }
        /* ================================================== */
        
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
        <style>
        /* Force Absolute Green Theme (Overriding all Bootstrap Blues/Cyans/Yellows) */
        .text-primary, .text-info, .text-warning { color: #2e7d32 !important; }
        .bg-primary, .bg-info, .bg-warning { background-color: #2e7d32 !important; color: #fff !important; }
        .badge-primary, .badge-info, .badge-warning { background-color: #2e7d32 !important; color: #fff !important; border-color: #2e7d32 !important; }
        .btn-primary, .btn-info { background-color: #2e7d32 !important; border-color: #2e7d32 !important; color: #fff !important; }
        .border-primary, .border-info, .border-warning { border-color: #2e7d32 !important; }
        
        /* Fix tag styling in accommodation and banquet */
        .badge.border, .badge-secondary, span[style*="#e9ecef"] { 
            background-color: rgba(255,255,255,0.1) !important; 
            backdrop-filter: blur(5px) !important; 
            border: 1px solid rgba(255,255,255,0.2) !important; 
            color: #ffffff !important;
        }
        .accomodation_area i.fa, .dining_area i.fa, .rules_area i.fa { color: #2e7d32 !important; }
        
        /* Improve Contact Form Glass Input Design */
        .form-control, input.form-control, textarea.form-control {
            background: rgba(0, 0, 0, 0.2) !important;
            backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1) !important;
            color: #ffffff !important;
        }
        
        /* Fix Active and Hover Menu Links (Override style.css cyan) */
        .navbar .menu_nav .nav-item:hover .nav-link, 
        .navbar .menu_nav .nav-item.active .nav-link,
        .header_area .navbar .nav .nav-item.active .nav-link {
            color: #2e7d32 !important;
        }
        
        /* Fix any stray anchor tags that are blue */
        a:hover, a:focus { color: #2e7d32; }
        
        /* Exemptions */
        .badge-danger { background-color: rgba(220,53,69,0.8) !important; color: #fff !important; }
        .text-danger, i.fa-ban { color: #dc3545 !important; }

        /* Professional Luxury Flatpickr Calendar Styling */
        .flatpickr-calendar {
            background: #111d13 !important;
            border: 1px solid rgba(46, 125, 50, 0.4) !important;
            border-radius: 14px !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.05) !important;
            font-family: 'Outfit', 'Poppins', sans-serif !important;
            padding: 10px !important;
            width: 320px !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
        }
        .flatpickr-calendar::before, .flatpickr-calendar::after {
            border-bottom-color: #111d13 !important;
        }
        .flatpickr-months {
            padding: 6px 0 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            margin-bottom: 8px !important;
        }
        .flatpickr-months .flatpickr-month {
            color: #ffffff !important;
            fill: #ffffff !important;
            height: 38px !important;
        }
        .flatpickr-current-month {
            font-size: 110% !important;
            font-weight: 600 !important;
            padding-top: 4px !important;
        }
        .flatpickr-current-month .cur-month {
            font-weight: 600 !important;
            color: #ffffff !important;
        }
        .flatpickr-current-month input.cur-year {
            font-weight: 600 !important;
            color: #2e7d32 !important;
        }
        .flatpickr-months .flatpickr-prev-month, 
        .flatpickr-months .flatpickr-next-month {
            color: #ffffff !important;
            fill: #ffffff !important;
            padding: 8px !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
        }
        .flatpickr-months .flatpickr-prev-month:hover, 
        .flatpickr-months .flatpickr-next-month:hover {
            background: rgba(46, 125, 50, 0.25) !important;
            color: #4caf50 !important;
            fill: #4caf50 !important;
        }
        span.flatpickr-weekday {
            color: #81c784 !important;
            font-weight: 600 !important;
            font-size: 13px !important;
        }
        .flatpickr-day {
            color: #e0e0e0 !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            height: 38px !important;
            line-height: 38px !important;
            border: 1px solid transparent !important;
            transition: all 0.18s ease-in-out !important;
        }
        .flatpickr-day:hover {
            background: rgba(46, 125, 50, 0.25) !important;
            border-color: rgba(76, 175, 80, 0.4) !important;
            color: #ffffff !important;
        }
        .flatpickr-day.today {
            border-color: #2e7d32 !important;
            color: #81c784 !important;
            font-weight: 700 !important;
        }
        .flatpickr-day.selected, 
        .flatpickr-day.startRange, 
        .flatpickr-day.endRange {
            background: linear-gradient(135deg, #2e7d32, #1b5e20) !important;
            border-color: #4caf50 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.5) !important;
        }
        .flatpickr-day.flatpickr-disabled, 
        .flatpickr-day.flatpickr-disabled:hover {
            color: rgba(255, 255, 255, 0.2) !important;
            cursor: not-allowed !important;
        }
        .flatpickr-day.prevMonthDay, 
        .flatpickr-day.nextMonthDay {
            color: rgba(255, 255, 255, 0.22) !important;
        }

        /* Booking input styling for calendar */
        .hotel_booking_table .input-group {
            cursor: pointer;
        }
        .hotel_booking_table .input-group input.form-control {
            cursor: pointer !important;
            background: rgba(255, 255, 255, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            border-right: none !important;
            color: #ffffff !important;
            font-weight: 500 !important;
            letter-spacing: 0.3px;
        }
        .hotel_booking_table .input-group .input-group-addon {
            background: rgba(46, 125, 50, 0.35) !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            border-left: none !important;
            color: #81c784 !important;
            padding: 0 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .hotel_booking_table .input-group:hover .input-group-addon {
            background: #2e7d32 !important;
            color: #ffffff !important;
        }

        /* Ensure all dropdown options are always legible on all operating systems */
        select option,
        select optgroup,
        .form-control option,
        .nice-select .option {
            background-color: #1e293b !important;
            color: #ffffff !important;
            font-size: 15px !important;
            padding: 8px 12px !important;
        }
        select option:checked,
        select option:hover {
            background-color: #2e7d32 !important;
            color: #ffffff !important;
        }
        </style>
    </head>
    <body>
        <!--================Header Area =================-->
        <header class="header_area">
            <div class="container-fluid" style="padding: 0;">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <!-- Brand -->
                    <a class="navbar-brand logo_h" href="./">
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
                            <li class="nav-item <?php echo in_array($current_page, ['index', 'home']) ? 'active' : ''; ?>"><a class="nav-link" href="./">Home</a></li> 
                            <li class="nav-item <?php echo in_array($current_page, ['accomodation', 'accommodation', 'rooms']) ? 'active' : ''; ?>"><a class="nav-link" href="accomodation">Rooms</a></li>
                            <li class="nav-item <?php echo in_array($current_page, ['restaurant', 'dining']) ? 'active' : ''; ?>"><a class="nav-link" href="dining">Dining</a></li>
                            <li class="nav-item <?php echo ($current_page == 'banquet') ? 'active' : ''; ?>"><a class="nav-link" href="banquet">Banquet &amp; Conference</a></li>
                            <li class="nav-item <?php echo in_array($current_page, ['rules', 'about']) ? 'active' : ''; ?>"><a class="nav-link" href="rules">Rules & Policies</a></li>
                            <li class="nav-item <?php echo ($current_page == 'gallery') ? 'active' : ''; ?>"><a class="nav-link" href="gallery">Gallery</a></li>
                            <li class="nav-item <?php echo ($current_page == 'contact') ? 'active' : ''; ?>"><a class="nav-link" href="contact">Contact</a></li>
                        </ul>
                    </div> 
                </nav>
            </div>
        </header>
        <!--================Header Area =================-->
