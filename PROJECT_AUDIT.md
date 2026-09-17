# PROJECT_AUDIT.md

## 1️⃣ Current Project Structure
```
royal-master/
├─ .gitattributes
├─ README.md
├─ admin_royal_master/               # Existing admin UI (demo, not usable)
│   └─ spark‑admin‑1.0.0/            # Placeholder admin package
├─ css/                               # CSS assets (Bootstrap, custom styles)
│   ├─ bootstrap.css
│   ├─ font-awesome.min.css
│   ├─ style.css
│   └─ responsive.css
├─ fonts/                             # Font files used by the template
├─ image/                             # Images used in the demo hotel template
├─ js/                                # JavaScript assets (jQuery, plugins)
│   ├─ jquery-3.2.1.min.js
│   ├─ popper.js
│   ├─ bootstrap.min.js
│   ├─ owl-carousel.min.js
│   ├─ jquery.ajaxchimp.min.js
│   ├─ mail-script.js
│   ├─ bootstrap-datepicker.min.js
│   ├─ jquery.nice-select.js
│   ├─ stellar.js
│   ├─ simpleLightbox.min.js
│   └─ custom.js
├─ vendors/                           # Vendor plugins (linericon, owl‑carousel, …)
├─ includes/                          # **Newly added** reusable PHP includes
│   ├─ header.php                     # Header + navigation (already created)
│   └─ footer.php                     # Footer + script includes (already created)
├─ index.html                         # Original demo HTML (kept for reference)
├─ about.html
├─ accomodation.html
├─ blog.html
├─ blog-single.html
├─ contact.html
├─ gallery.html
├─ elements.html
├─ prepros-6.config
├─ royal-Doc/                        # Documentation folder (unused in website)
├─ scss/                             # SCSS sources (not compiled in demo)
├─ index.php                         # **Converted** Core‑PHP entry page
├─ about.php
├─ accomodation.php
├─ blog.php
├─ blog‑single.php
├─ contact.php
├─ gallery.php
├─ elements.php
└─ (future) admin/                  # Will be created in Phase 3
```

## 2️⃣ Existing Functionality (Pre‑Conversion)
| Page | Type | Key Features |
|------|------|--------------|
| `index.html` | Home page | Hero banner, room‑type showcase, recent blog, footer. |
| `about.html` | Static page | Resort description (demo text). |
| `accomodation.html` | Static page | List of rooms (demo content). |
| `gallery.html` | Static gallery | Image grid with lightbox. |
| `blog.html` / `blog-single.html` | Blog list & detail | Sample blog posts. |
| `contact.html` | Contact form (client‑side only) | Sends data to `contact_process.php` (no server‑side validation). |
| `elements.html` | Misc UI elements demo. |
| `contact_process.php` | PHP file that simply emails the form data (no sanitisation). |

**Converted PHP files** (`*.php`) now include `header.php` and `footer.php` and output the same markup but with dynamic `$page_title`/`$current_page` variables.

## 3️⃣ Reusable Components Identified
- **Header & Navigation** – already extracted into `includes/header.php`.
- **Footer** – extracted into `includes/footer.php` (contains copyright, social icons, script tags).
- **Bootstrap CSS & JS** – common across all pages, can stay as‑is.
- **jQuery plugins** (owl‑carousel, lightbox, nice‑select, datepicker) – may be kept if needed for the resort UI.
- **Image assets** – currently hotel‑specific; will be replaced with resort images but folder structure can stay.

## 4️⃣ Demo Content that Must be Replaced
| File | Content to replace |
|------|--------------------|
| `index.php` (and other `*.php`) | All placeholder texts (e.g., "Royal Hotel", generic room names, lorem ipsum). |
| `about.php` | Resort description, history, location. |
| `accomodation.php` | Real room categories (Deluxe Cottage, Deluxe Premium, Family Premium) and their details. |
| `gallery.php` | Real resort photos (high‑quality WebP/AVIF). |
| `blog.php` / `blog‑single.php` | Either remove (if no blog) or populate with actual resort news. |
| `contact.php` | Update contact info, add server‑side validation. |
| `contact_process.php` | Replace with a secure, prepared‑statement based handler. |
| `admin_royal_master/` | Current demo admin is unusable; will be replaced with a **secure custom admin panel** in Phase 3. |

## 5️⃣ Security Weaknesses (Current)
1. **Direct access to `contact_process.php`** – no input sanitisation, possible XSS/Email injection.
2. **No database connection** – all pages are static; when we add DB we must use **PDO with prepared statements**.
3. **Potential exposure of configuration** – there is no `.env` file yet; any future DB credentials must be stored **outside web‑root** or protected via `.htaccess`.
4. **Error reporting** – development server likely shows PHP warnings; production must set `display_errors = Off`.
5. **No CSRF protection** – all forms lack CSRF tokens.
6. **File uploads (future admin panel)** – currently none, but admin UI will need strict validation.
7. **Session handling** – no admin login yet; when built we must regenerate session ID after login and use `httponly`/`secure` flags.
8. **Hard‑coded URLs & phone numbers** – should be stored in a `site_settings` table.

## 6️⃣ Performance Issues (Current)
- **Large images** (JPEG ≈ 300 KB+ each) – will need conversion to WebP/AVIF and proper dimensions.
- **Unused CSS/JS** – many vendor files (e.g., `bootstrap-datepicker`, `nice-select`) are loaded on every page but rarely used.
- **Render‑blocking CSS** – all CSS files are linked in `<head>`; critical CSS (bootstrap & custom) could be inlined / preloaded.
- **No caching headers** – `.htaccess` does not set `Cache‑Control` for static assets.
- **No minification** – CSS/JS are not minified.
- **No lazy‑loading** for below‑the‑fold images.

## 7️⃣ SEO Deficiencies (Current)
| Issue | Description |
|-------|-------------|
| Missing meta description | Most pages have only `<title>`.
| Duplicate titles | Several pages still use the default "Royal Hotel" title.
| No canonical URLs | Not set, leading to potential duplicate content.
| No structured data | No `LocalBusiness` / `Hotel` JSON‑LD.
| Image `alt` attributes | Many demo images have empty `alt`.
| No XML sitemap / robots.txt | Not present.
| Breadcrumbs | Not implemented.
| 404 page | Missing.

## 8️⃣ Recommendations & Next Steps
1. **Create a `site_settings` table** to hold site‑wide values (resort name, contact numbers, logo path, social URLs). Populate with placeholders now.
2. **Build the admin panel** (Phase 3) with secure login, CSRF, file‑upload handling, and CRUD for all entities.
3. **Implement a global configuration file** (`config.php`) that loads credentials from a non‑web‑accessible directory and defines a PDO instance.
4. **Replace all demo texts** with real resort content (will be done gradually in later phases).
5. **Optimize assets** – convert images, minify CSS/JS, add lazy‑loading, set cache headers.
6. **Add SEO foundation** – generate meta tags from DB, include JSON‑LD, create sitemap and robots.txt.
7. **Perform security audit** after Phase 3 (admin) and before going live.
8. **Maintain `PROJECT_CHANGE_LOG.md`** – each modification will be recorded automatically.

---

**Prepared by:** Antigravity (AI coding assistant) 
**Date:** 2026‑09‑11

---

*Next step:* Review this audit. Once approved, we will move to **Phase 2 – Database Architecture**.
