# Parijat Resort Bolpur & Banquet

Always first commit to main and then create another branch.

---

## 📁 Professional Project Structure

```
parijat_resort_bolpur_banquet/
│
├── .env                              # Active environment configuration (never commit credentials)
├── .env.example                      # Template for environment settings (DB, SMTP, App URL)
├── .gitignore                        # Git exclusion rules
├── .htaccess                         # Security headers, file blocking & Front Controller routing
│
├── database/                         # Database migrations and seed data
│   └── schema.sql                    # Master production SQL schema (19 tables + full seed data)
│
├── docs/                             # Production operations & architecture manuals
│   └── DEPLOYMENT_AND_PUBLIC_HTML_GUIDE.md  # Subdomain hosting & public_html guide
│
├── includes/                         # Core application services & backend logic
│   ├── bootstrap.php                 # Core paths & HTTP security header bootstrap
│   ├── config.php                    # PDO database connection & session settings
│   ├── env.php                       # Zero-dependency .env environment parser
│   ├── router.php                    # Front Controller router engine
│   ├── email.php                     # PHPMailer SMTP mail wrapper
│   ├── header.php                    # Site navigation & dynamic SEO head
│   ├── footer.php                    # Site footer & script includes
│   └── PHPMailer/                    # SMTP vendor library
│
├── admin/                            # Administrative Back-Office Portal
│   ├── index.php                     # Admin Overview Dashboard
│   ├── login.php                     # Secure admin login with brute-force protection
│   ├── auth.php                      # Session authentication middleware
│   ├── bookings.php                  # Reservation enquiry management
│   ├── edit_booking.php              # Reservation editor
│   ├── room_categories.php           # 19 Rooms / 4 categories manager
│   ├── room_rates.php                # Tariff manager (Base, Weekend, Seasonal)
│   ├── room_images.php               # Room photo manager
│   ├── restaurant.php                # Labanya's Heshel Ghar menu manager
│   ├── restaurant_timings.php        # Dining operating hours manager
│   ├── banquet.php                   # Banquet hall & lawn manager
│   ├── gallery.php                   # Public photo gallery manager
│   ├── amenities.php                 # Amenity icon & feature manager
│   ├── contacts.php                  # Contact form inquiries review
│   ├── guests.php                    # Guest records
│   ├── policies.php                  # 18 Resort rules & policies editor
│   ├── profile.php                   # Admin profile settings
│   ├── reports.php                   # Reporting metrics
│   ├── seo_settings.php              # Dynamic SEO & OpenGraph manager
│   ├── site_settings.php             # Contact numbers, WhatsApp & leadership editor
│   ├── create_seed_admin.php         # Initial admin creation script (delete after use)
│   ├── assets/                       # Admin theme CSS, JS & icons
│   └── includes/                     # Admin header, footer & sidebar
│
├── assets/                           # User-generated content & uploads
│   ├── js/                           # Custom site scripts
│   └── uploads/                      # Room, gallery & banquet image uploads (chmod 755)
│
├── css/                              # Compiled stylesheets
├── js/                               # Frontend JavaScript plugins & custom scripts
├── fonts/                            # Web font icon assets
├── image/                            # Resort photographs & banner assets
├── vendors/                          # Third-party frontend libraries (Owl, Lightbox, etc.)
│
├── index.php                         # Front Controller entry point & Homepage
├── about.php                         # About Resort & Management page
├── accomodation.php                  # Rooms, Cottages & Tariffs showcase
├── banquet.php                       # AC Banquet Hall & Event showcase
├── booking.php                       # Instant room enquiry modal & page
├── book_process.php                  # Booking server-side validator & WhatsApp redirect
├── confirmation.php                  # Booking request confirmation & summary
├── contact.php                       # Contact Desk, Management profiles & Form
├── contact_process.php               # Contact form handler & DB inquiry recorder
├── dining.php                        # Labanya's Heshel Ghar dining page
├── gallery.php                       # High-definition photo gallery
├── rules.php                         # 18 Resort Guidelines & Policies page
├── 404.php                           # Custom branded 404 error page
├── robots.txt                        # Search engine crawling rules
└── sitemap.xml                       # XML sitemap for SEO
```

---

## 🚀 Quick Start (Local Setup)

1. **Database:**
   - Create a database named `parijat_resort` in MySQL.
   - Import `database/schema.sql`.

2. **Environment Configuration:**
   - Copy `.env.example` to `.env`.
   - Configure your local database credentials (`DB_HOST`, `DB_USER`, `DB_PASS`).

3. **Run Application:**
   - Access via `http://localhost/parijat_resort_bolpur_banquet/`.
   - Admin panel: `http://localhost/parijat_resort_bolpur_banquet/admin/`.

---

## 📖 Deployment Guides

For complete live subdomain deployment and migrating to a production two-tier `public_html` architecture, refer to:
- [`docs/DEPLOYMENT_AND_PUBLIC_HTML_GUIDE.md`](docs/DEPLOYMENT_AND_PUBLIC_HTML_GUIDE.md)