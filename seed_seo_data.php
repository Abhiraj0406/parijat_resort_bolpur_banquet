<?php
require_once 'includes/config.php';

$seoPages = [
    [
        'page_name' => 'index',
        'meta_title' => 'Parijat Resort Bolpur | Best Resort in Bolpur Shantiniketan',
        'meta_description' => 'Experience serene luxury at Parijat Resort Bolpur near Shantiniketan. 19 convertible AC/Non-AC rooms with balcony, swimming pool, and authentic dining.',
        'meta_keywords' => 'Parijat Resort Bolpur, Resort in Bolpur, Resort in Bolpur Shantiniketan, Best resort in Bolpur, Family resort in Bolpur, Resort near Shantiniketan, Hotels resort accommodation in Bolpur',
        'canonical_url' => 'https://parijatresortbolpur.com/',
        'og_title' => 'Parijat Resort Bolpur | Luxury Nature Resort in Bolpur',
        'og_description' => 'Stay, relax and reconnect at Parijat Resort Bolpur. 19 cottages & rooms with balconies, swimming pool, banquet and multi-cuisine restaurant.',
        'og_image' => 'image/room1.jpg'
    ],
    [
        'page_name' => 'accomodation',
        'meta_title' => 'Rooms & Cottages Tariff | Parijat Resort Bolpur',
        'meta_description' => 'Explore all 19 deluxe cottage and premium rooms with private balcony at Parijat Resort Bolpur. Convertible AC/Non-AC options with direct WhatsApp booking.',
        'meta_keywords' => 'Parijat Resort room tariff, Resort in Bolpur room rates, Deluxe cottage rooms Bolpur, Accommodation in Bolpur Shantiniketan',
        'canonical_url' => 'https://parijatresortbolpur.com/accomodation.php',
        'og_title' => 'Rooms & Cottages Tariff | Parijat Resort Bolpur',
        'og_description' => 'Deluxe cottage rooms with balcony, Family premium suites, and flexible AC/Non-AC room options at Parijat Resort Bolpur.',
        'og_image' => 'image/room2.jpg'
    ],
    [
        'page_name' => 'contact',
        'meta_title' => 'Contact & Direct Booking | Parijat Resort Bolpur',
        'meta_description' => 'Contact Parijat Resort Bolpur management for room reservations, banquet hall bookings, and swimming pool access. Direct call and WhatsApp support.',
        'meta_keywords' => 'Parijat Resort contact number, Parijat Resort WhatsApp booking, Bolpur resort phone number, Resort near Shantiniketan booking',
        'canonical_url' => 'https://parijatresortbolpur.com/contact.php',
        'og_title' => 'Contact & Booking Desk | Parijat Resort Bolpur',
        'og_description' => 'Call +91 98302 76364 or WhatsApp +91 98309 09875 for immediate reservations and enquiries at Parijat Resort Bolpur.',
        'og_image' => 'image/contact_og.jpg'
    ],
    [
        'page_name' => 'rules',
        'meta_title' => 'Resort Rules & Regulations | Parijat Resort Bolpur',
        'meta_description' => 'Official guest guidelines, check-in (12:30 PM), check-out (11:00 AM), dining timings, cancellation, and swimming pool rules for Parijat Resort Bolpur.',
        'meta_keywords' => 'Parijat Resort rules, Resort check-in time Bolpur, Hotel policies Bolpur Shantiniketan, Resort cancellation policy Bolpur',
        'canonical_url' => 'https://parijatresortbolpur.com/rules.php',
        'og_title' => 'Resort Rules & Guest Policies | Parijat Resort Bolpur',
        'og_description' => 'Review our 18 guest regulations covering check-in/out, restaurant schedule, cancellation carry-forward, pool timings, and room cleaning.',
        'og_image' => 'image/room3.jpg'
    ],
    [
        'page_name' => 'about',
        'meta_title' => 'About Us | Parijat Resort Bolpur Shantiniketan',
        'meta_description' => 'Learn about Parijat Resort Bolpur, nestled in peaceful greenery near Shantiniketan. Experience authentic Bengali hospitality, fine dining and modern comfort.',
        'meta_keywords' => 'About Parijat Resort Bolpur, Resort in Bolpur history, Shantiniketan hospitality, Best family resort Bolpur',
        'canonical_url' => 'https://parijatresortbolpur.com/about.php',
        'og_title' => 'About Parijat Resort Bolpur',
        'og_description' => 'Discover our serene 19-room property with banquet facilities, garden swimming pool, and authentic Bengali cuisine.',
        'og_image' => 'image/about_banner.jpg'
    ],
    [
        'page_name' => 'gallery',
        'meta_title' => 'Photo Gallery | Parijat Resort Bolpur',
        'meta_description' => 'View high quality photos of Parijat Resort Bolpur: deluxe cottage rooms, swimming pool, dining hall, lush gardens, and banquet facilities.',
        'meta_keywords' => 'Parijat Resort photos, Bolpur resort photos, Shantiniketan resort gallery, Swimming pool photos Bolpur resort',
        'canonical_url' => 'https://parijatresortbolpur.com/gallery.php',
        'og_title' => 'Photo Gallery | Parijat Resort Bolpur',
        'og_description' => 'Explore the scenic beauty and luxury rooms of Parijat Resort Bolpur through our photo gallery.',
        'og_image' => 'image/gallery/01.jpg'
    ]
];

$stmt = $pdo->prepare("INSERT INTO seo_settings (page_name, meta_title, meta_description, meta_keywords, canonical_url, og_title, og_description, og_image)
    VALUES (:p, :t, :d, :k, :c, :ot, :od, :oi)
    ON DUPLICATE KEY UPDATE 
    meta_title = VALUES(meta_title),
    meta_description = VALUES(meta_description),
    meta_keywords = VALUES(meta_keywords),
    canonical_url = VALUES(canonical_url),
    og_title = VALUES(og_title),
    og_description = VALUES(og_description),
    og_image = VALUES(og_image)");

foreach ($seoPages as $page) {
    $stmt->execute([
        ':p' => $page['page_name'],
        ':t' => $page['meta_title'],
        ':d' => $page['meta_description'],
        ':k' => $page['meta_keywords'],
        ':c' => $page['canonical_url'],
        ':ot' => $page['og_title'],
        ':od' => $page['og_description'],
        ':oi' => $page['og_image']
    ]);
    echo "Seeded SEO for: " . $page['page_name'] . "\n";
}
echo "SEO seeding complete!\n";
