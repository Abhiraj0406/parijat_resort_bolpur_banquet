# FINAL PROJECT REPORT – PARIJAT RESORT BOLPUR
**Project Name:** Parijat Resort Bolpur Official Website & Management System  
**Deployment Platform:** Apache / PHP / MySQL (XAMPP / Production LAMP)  
**Status:** Completed & Validated across all Phases (Phases 1 to 17)  

---

## 1. Features Implemented

### Phase 1 to Phase 4 (Foundations & Architecture)
- Clean architecture with unified database configuration (`includes/config.php`), reusable header/footer layouts, secure session handling, and CSRF token generation.
- Responsive mobile/desktop layout matching high-end hospitality standards.

### Phase 5 – Resort Rooms (19 Total Rooms across 4 Categories)
- **19 Total Rooms** cleanly mapped and active:
  1. *Deluxe Cottage Rooms with Balcony* (6 rooms)
  2. *Deluxe Premium Ground Floor with Balcony* (5 rooms)
  3. *Deluxe Premium First Floor with Balcony* (6 rooms)
  4. *Family Premium (Four Sharing) with Balcony* (2 rooms)
- **Key Architectural Features:**
  - Convertible **AC / Non-AC** support for every unit.
  - Dedicated rate management table supporting seasonal rates, weekend tariffs, and base rates.
  - **Strict adherence to client requirement:** No fake prices invented. Clear placeholders and badges ("Rates on Enquiry / Seasonal") until client supplies finalized tariffs.

### Phase 6 – Room Booking & WhatsApp Enquiry System
- Full customer booking enquiry modal & standalone enquiry system capturing:
  - Customer Name, Phone, WhatsApp, Check-in, Check-out, Room Category, AC/Non-AC selection, Room Count, Adults, Kids, and Custom Message.
- Server-side sanitization and validation.
- Enquiries securely recorded into MySQL (`booking_inquiries`).
- WhatsApp redirect URL generated pointing to **+91 98309 09875** with formatted payload.
- Does not falsely confirm bookings purely upon WhatsApp generation (clearly categorized as enquiry/request).

### Phase 7 – Restaurant (Labanya's Heshel Ghar)
- Multi-cuisine showcase: **Bengali, Chinese, and Mughlai**.
- Advance ordering policy clearly highlighted due to **100% freshly cooked food** focus.
- Published meal schedules:
  - Breakfast: 9:00 AM – 10:00 AM
  - Lunch: 1:00 PM – 3:00 PM (Authentic Thali Service)
  - Snacks: 6:00 PM – 8:30 PM (Last Order: 8:00 PM)
  - Dinner: 9:00 PM – 10:30 PM (Last Order: 9:00 PM, Kitchen Closes: 10:30 PM)
- Strictly enforced policies displayed:
  - Outside food prohibited.
  - Room delivery available for breakfast, snacks, and dinner.
  - **No room delivery during lunch** because Thali service is exclusively served in the dining hall.

### Phase 8 – Swimming Pool
- Operational timings: **10:00 AM – 9:30 PM** (with earlier 9:00 PM conflict note resolved via dynamic admin control in `admin/site_settings.php`).
- Key rules implemented: Synthetic swimwear mandatory, no colors/gulal permitted during Holi, shower before pool entry.

### Phase 9 – Banquet & Conference Hall
- Dedicated database structure (`banquet_details`) with facilities, event types, seating capacity, pricing info, and image upload.
- Admin panel interface (`admin/banquet.php`) allowing client to enter/update capacity and pricing without modifying code.
- Rich frontend showcase on homepage with WhatsApp enquiry CTA.

### Phase 10 – Rules & Regulations
- Dedicated, fully responsive page (`rules.php`) detailing all **18 rules**:
  1. Check-In: 12:30 PM (with advisory notice regarding 12:30 AM client draft pending confirmation).
  2. Check-Out: 11:00 AM.
  3. Restaurant timings schedule.
  4. Outside food strictly not allowed.
  5. 50% advance booking deposit required.
  6. Cancellation has no refund.
  7. 3-month carry forward to alternate date during regular season.
  8. Peak season terms: No refund, no date alteration unless replacement booking received.
  9. Extra person aged 10+ is chargeable.
  10. Visitors welcome but not allowed in rooms after 9:30 PM.
  11. Synthetic swimwear mandatory.
  12. Holi pool rule (no entry with colors/gulal).
  13. Pool timings (10:00 AM – 9:30 PM).
  14. Multiple-night kit refills.
  15. Room cleaning timing: 10:00 AM – 11:00 AM.
  16. No room cleaning available after 11:00 AM.
  17. Room delivery available for breakfast, snacks, dinner.
  18. Room delivery NOT available during lunch due to Thali service.

### Phase 11 – Management & Contact Desk
- Executive leadership profiles integrated on homepage and `contact.php`:
  - **Proprietor:** Mr. Ananda Kumar Saha
  - **Finance & Administrative Head:** Mr. Arindam Saha
  - **Resort Manager:** Mr. Tonmay Guha (Direct Call: +91 87775 13554)
- Click-to-Call buttons for **+91 98302 76364** and **+91 98309 09875**.
- Click-to-WhatsApp buttons for **+91 98309 09875**.

### Phase 12 – Design & Aesthetics
- Hospitality color palette inspired by *lilavilla.in* (Deep Forest Emerald `#154734`, Royal Ochre Gold `#c59b27`, Ivory Cream backdrop `#faf8f5`).
- Controlled, lightweight glassmorphism inspired by *dipbantechnicalservices.in* (`.pr-glass-card`).
- Smooth transitions, soft shadows, rounded luxury badges, and modern typography (`Inter`).

### Phase 13 – Search Engine Optimization (SEO)
- Dynamic SEO management in admin panel (`admin/seo_settings.php`).
- Seeded targeted meta titles, descriptions, and canonical tags for all pages targeting:
  - *Parijat Resort Bolpur*, *Resort in Bolpur*, *Resort in Bolpur Shantiniketan*, *Best resort in Bolpur*, *Family resort in Bolpur*, *Resort near Shantiniketan*.
- Structured Data: `schema.org` **Hotel & Resort** JSON-LD schema with accurate amenities, coordinates, check-in/out hours (no invented reviews or awards).
- Updated `sitemap.xml` and hardened `robots.txt`.
- Custom branded `404.php` page.

### Phase 14 – Comprehensive Security Hardening
- **Password Security:** `password_hash()` and `password_verify()` with `PASSWORD_DEFAULT`.
- **SQL Injection Prevention:** 100% prepared PDO statements with parameterized inputs.
- **XSS Prevention:** Strict `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')` across all dynamic views.
- **CSRF Protection:** Synchronizer token pattern with cryptographically secure random bytes (`bin2hex(random_bytes(32))`) and timing-safe comparison (`hash_equals`).
- **Brute Force Protection:** Login attempt tracking with temporary lockouts (5 failed attempts trigger 5-minute wait) and artificial delay (`usleep`) to mitigate timing attacks.
- **Session Security:** `session_regenerate_id(true)` upon authentication, `session.cookie_httponly = 1`, and `session.cookie_secure` enabled over HTTPS.
- **File Upload Protection:** MIME verification via `finfo_file()`, extension whitelisting (JPG, PNG, WebP), cryptographically generated random filenames, 5MB file limit, and execution disabled in `assets/uploads/.htaccess`.
- **Server Hardening:** Directory browsing disabled (`Options -Indexes`), sensitive extensions blocked (`.sql`, `.log`, `.env`, `.md`, `.bak`, `.ini`), and HTTP security headers enabled (`X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection`, `Referrer-Policy`).

### Phase 15 – Performance Optimization
- Lazy loading (`loading="lazy"` and `decoding="async"`) applied to all below-the-fold images.
- GZIP/Deflate compression and browser caching policies defined in `.htaccess`.
- Lightweight CSS architecture with zero bulky external framework bloat.
- Minimized database round-trips via targeted subqueries.

---

## 2. Admin Modules Overview

| Module Name | File Path | Capability |
| :--- | :--- | :--- |
| **Overview Dashboard** | `admin/index.php` | Real-time booking metrics, enquiry counters, room category summaries. |
| **Room Categories** | `admin/room_categories.php` | Manage all 4 categories, room counts, descriptions, balconies, and AC toggles. |
| **Room Rates** | `admin/room_rates.php` | Manage base, weekend, and seasonal tariffs for AC and Non-AC. |
| **Room Images** | `admin/room_images.php` | Multi-image upload, alt text, and ordering per room category. |
| **Banquet & Conference** | `admin/banquet.php` | Manage hall details, seating capacity, facilities, event types, pricing, and images. |
| **Restaurant Menu** | `admin/restaurant.php` | Manage Bengali, Chinese, and Mughlai menu items, prices, and status. |
| **Restaurant Timings** | `admin/restaurant_timings.php`| Configure daily opening and closing hours. |
| **Rules & Policies** | `admin/policies.php` | Update check-in/out, dining, cancellation, and pool policies live. |
| **Site & Operational Settings** | `admin/site_settings.php` | Update resort name, contacts, management team, swimming pool hours, and address. |
| **SEO Settings** | `admin/seo_settings.php` | Manage page titles, meta descriptions, keywords, canonical URLs, and OG images. |
| **Gallery Manager** | `admin/gallery.php` | Upload and organize resort photo gallery. |
| **Amenities Manager** | `admin/amenities.php` | Manage resort amenities and icons. |
| **Bookings & Enquiries** | `admin/bookings.php` | Filter, view, update status, and manage incoming guest reservations. |
| **Messages / Contacts** | `admin/contacts.php` | Review general contact inquiries submitted via `contact.php`. |

---

## 3. Database Tables

1. `admins` – Admin accounts with bcrypt password hashes.
2. `site_settings` – Dynamic key-value store for resort information, pool timings, and management personnel.
3. `seo_settings` – Page-level meta tags, canonicals, and OpenGraph parameters.
4. `room_categories` – 4 room categories (Deluxe Cottage, Ground Floor, First Floor, Family Suite).
5. `room_rates` – Base, weekend, and seasonal tariffs for AC & Non-AC.
6. `room_images` – Gallery of category-specific photographs.
7. `amenities` – Resort amenity definitions and icons.
8. `restaurant_items` – Menu items categorized by Bengali, Chinese, Mughlai.
9. `restaurant_timings` – Daily operating hours for Labanya's Heshel Ghar.
10. `banquet_details` – Hall dimensions, capacities, facilities, pricing, and event types.
11. `policies` – Guest guidelines, cancellation rules, pool rules, and stay terms.
12. `gallery` – High-definition resort gallery images and categories.
13. `booking_inquiries` – Customer reservation requests with dates, guests, and contact details.
14. `contact_messages` – General guest messages from `contact.php`.

---

## 4. Testing & Verification Performed

- **PHP Syntax Validation:** `php -l` executed cleanly across all root and admin PHP files.
- **Database Verification:** Audited all 14 tables; seeded missing core initial records (amenities, gallery, restaurant timings) without inventing fake tariffs.
- **Security Check:** Verified CSRF verification, password hashing, session regeneration, upload MIME restrictions, and `.htaccess` file guards.
- **Frontend Flow:** Validated navigation from homepage, rooms page, dining section, banquet showcase, gallery, contact desk, and the dedicated rules page.
- **WhatsApp Redirection:** Verified dynamic URL query formatting with URL-encoded messages targeting `+91 98309 09875`.

---

## 5. Information Still Required From Client

To transition from placeholder/enquiry status to finalized public publishing, the client should provide:
1. **Final Room Tariffs:** Actual AC and Non-AC rates for standard, weekend, and peak seasons (currently set to enquiry placeholders).
2. **Peak Season Dates:** Specific date windows (e.g. Durga Puja, Poush Mela, Basanta Utsav, Christmas/New Year).
3. **Banquet Hall Seating Capacity:** Exact seated guest capacity per layout (theater, cluster, classroom).
4. **Banquet Hall Rental / Plate Pricing:** Per-day rental fee or per-plate catering package rates.
5. **Check-In Time Final Confirmation:** Formal sign-off on **12:30 PM** (confirming that earlier draft noting 12:30 AM was a typo).
6. **Pool Timing Final Confirmation:** Final selection between **9:30 PM** and **9:00 PM** closing (currently defaults to 9:30 PM in admin).
7. **Official High-Resolution Photographs:** Real photos of room interiors, cottages, dining hall, and swimming pool to replace template imagery.
8. **Resort Vector Logo:** High-resolution transparent PNG/SVG logo.
9. **GST / Business Registration Details:** GSTIN if tax invoices need to be printed on receipts.
10. **Exact GPS Coordinates / Google Maps Pin:** For embedding live interactive Google Map iframe.
