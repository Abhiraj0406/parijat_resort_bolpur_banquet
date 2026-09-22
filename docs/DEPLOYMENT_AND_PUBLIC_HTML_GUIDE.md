# PARIJAT RESORT BOLPUR & BANQUET
## Production Deployment, Subdomain Hosting & `public_html` Architectural Guide

---

## 📋 Table of Contents
1. [Architectural Overview](#1-architectural-overview)
2. [Part 1: Live Subdomain Hosting Guide](#2-part-1-live-subdomain-hosting-guide)
   - [Step 1: DNS Configuration](#step-1-dns-configuration)
   - [Step 2: Subdomain Creation (cPanel / Apache / Plesk)](#step-2-subdomain-creation)
   - [Step 3: Database Creation & Schema Import](#step-3-database-creation--schema-import)
   - [Step 4: Uploading Project Files](#step-4-uploading-project-files)
   - [Step 5: Configuring Environment Variables (.env)](#step-5-configuring-environment-variables-env)
   - [Step 6: SSL / HTTPS Activation](#step-6-ssl--https-activation)
   - [Step 7: Admin Panel Initialization & Hardening](#step-7-admin-panel-initialization--hardening)
3. [Part 2: Main Domain `public_html` Two-Tier Architecture Guide](#3-part-2-main-domain-public_html-two-tier-architecture-guide)
   - [Why Two-Tier Architecture?](#why-two-tier-architecture)
   - [Two-Tier Directory Layout Blueprint](#two-tier-directory-layout-blueprint)
   - [Step-by-Step Migration to `public_html`](#step-by-step-migration-to-public_html)
   - [Front Controller Path Bridging](#front-controller-path-bridging)
   - [File & Folder Permission Matrix](#file--folder-permission-matrix)
4. [Part 3: Security Hardening & Troubleshooting](#4-part-3-security-hardening--troubleshooting)

---

## 1. Architectural Overview

The **Parijat Resort Bolpur & Banquet** web application is engineered with:
- **Core Technology:** PHP 7.4 / 8.0+ with PDO (Prepared Statements).
- **Architecture:** Front Controller Architecture with clean URL rewriting (`.htaccess`).
- **Database:** MySQL 5.7+ / 8.0+ / MariaDB 10.3+ (`utf8mb4` encoding).
- **Environment Management:** Zero-dependency `.env` parser (`includes/env.php`) populating `$_ENV`, `$_SERVER`, and `getenv()`.
- **Security:** CSRF synchronizer tokens, bcrypt (`PASSWORD_DEFAULT`), session hijacking protection, HTTP security headers, and MIME-type upload filtering.

---

## 2. Part 1: Live Subdomain Hosting Guide

Use this section to deploy the project to a live subdomain (e.g., `resort.yourdomain.com` or `dev.parijatresortbolpur.com`).

### Step 1: DNS Configuration
Before configuring your hosting server, point your subdomain's DNS records to your server IP address:

1. Log in to your Domain Registrar or DNS Manager (Cloudflare, GoDaddy, Namecheap, etc.).
2. Add a new **A Record**:
   - **Type:** `A`
   - **Name / Host:** `resort` (or your chosen subdomain prefix)
   - **Target / Value:** `YOUR_SERVER_IP_ADDRESS` (e.g., `192.0.2.1`)
   - **TTL:** `Auto` or `300 seconds` (5 mins)
3. *Alternative (CNAME):*
   - **Type:** `CNAME`
   - **Name:** `resort`
   - **Target:** `yourdomain.com`

---

### Step 2: Subdomain Creation
#### Option A: In cPanel
1. Navigate to **Domains** &rarr; **Subdomains** (or **Domains** in newer cPanel versions).
2. Click **Create A New Domain**.
3. In the domain name field, enter: `resort.yourdomain.com`.
4. Untick **Share document root**.
5. Set the **Document Root** to a dedicated folder:
   - Example: `/home/cpanelusername/resort.yourdomain.com`
6. Click **Submit**.

#### Option B: In Apache VirtualHost (VPS / Dedicated Server)
Add the following virtual host definition in `/etc/apache2/sites-available/resort.conf`:
```apache
<VirtualHost *:80>
    ServerName resort.yourdomain.com
    DocumentRoot /var/www/resort/

    <Directory /var/www/resort/>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/resort_error.log
    CustomLog ${APACHE_LOG_DIR}/resort_access.log combined
</VirtualHost>
```
Enable site and rewrite module:
```bash
sudo a2enmod rewrite headers expires deflate
sudo a2ensite resort.conf
sudo systemctl reload apache2
```

---

### Step 3: Database Creation & Schema Import
1. In cPanel, navigate to **MySQL® Databases**:
   - Create Database: `cpaneluser_parijat`
   - Create User: `cpaneluser_parijatuser`
   - Set a strong password (e.g., `P@rijat#2026!SecureKey`)
   - Add user to database and grant **ALL PRIVILEGES**.
2. Open **phpMyAdmin**:
   - Select your newly created database (`cpaneluser_parijat`).
   - Click the **Import** tab.
   - Choose file: Select `database_master.sql` (or `database.sql`).
   - Click **Import** at the bottom.
   - Verify that all 19 tables are created and seeded successfully.

---

### Step 4: Uploading Project Files
1. Compress your project workspace into a `.zip` archive (ensure `.env` is NOT included in public zip; copy `.env.example`).
2. Open cPanel **File Manager** and navigate into your subdomain folder:
   - Path: `/home/cpaneluser/resort.yourdomain.com/`
3. Click **Upload** and upload your `.zip` archive.
4. Right-click the `.zip` file and select **Extract**.
5. Ensure files are directly in the subdomain root, not nested inside another subfolder.

---

### Step 5: Configuring Environment Variables (.env)
1. In cPanel File Manager, ensure **Show Hidden Files (dotfiles)** is enabled in Settings (top right gear icon).
2. In the subdomain root folder:
   - If `.env` does not exist, rename or copy `.env.example` to `.env`.
3. Open `.env` in Code Editor and update the production values:
   ```dotenv
   APP_NAME="Parijat Resort Bolpur"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://resort.yourdomain.com
   TIMEZONE=Asia/Kolkata

   # Live Database Credentials
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=cpaneluser_parijat
   DB_USER=cpaneluser_parijatuser
   DB_PASS=P@rijat#2026!SecureKey
   DB_CHARSET=utf8mb4

   # SMTP Outgoing Mail
   SMTP_HOST=mail.yourdomain.com
   SMTP_PORT=587
   SMTP_USER=reservations@yourdomain.com
   SMTP_PASS=YourEmailPassword
   SMTP_SECURE=tls
   FROM_EMAIL=reservations@yourdomain.com
   FROM_NAME="Parijat Resort Bolpur"
   ADMIN_EMAIL=reservations@yourdomain.com
   ```
4. Save changes.

---

### Step 6: SSL / HTTPS Activation
1. In cPanel, navigate to **SSL/TLS Status**.
2. Locate `resort.yourdomain.com` in the domain list.
3. Click **Run AutoSSL** (or install Let's Encrypt certificate).
4. Wait 2–5 minutes until the green lock icon displays.
5. In `.htaccess`, HTTPS is automatically supported. To enforce HTTPS redirect, verify `.htaccess` includes:
   ```apache
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

---

### Step 7: Admin Panel Initialization & Hardening
1. Navigate to your subdomain admin seed script in your browser:
   - URL: `https://resort.yourdomain.com/admin/create_seed_admin.php`
2. The script will generate a temporary Super Admin account and output your unique one-time password.
3. **IMMEDIATELY write down or store the generated credentials.**
4. Log in at `https://resort.yourdomain.com/admin/login.php`.
5. **CRITICAL SECURITY STEP:** Delete `create_seed_admin.php`:
   - In cPanel File Manager, navigate to `admin/` and delete `create_seed_admin.php`.
   - Or via SSH: `rm /home/cpaneluser/resort.yourdomain.com/admin/create_seed_admin.php`

---

## 3. Part 2: Main Domain `public_html` Two-Tier Architecture Guide

When you transition the website from a subdomain to the primary live domain (e.g., `https://parijatresortbolpur.com`), industry best practice dictates separating public web assets from private backend logic.

### Why Two-Tier Architecture?
In standard single-folder setups, if an Apache module fails, `.htaccess` is misconfigured, or PHP crashes, visitors can download `.env`, read database passwords, or inspect sensitive SQL backups directly in their browser.

Under the **Two-Tier Web Root** architecture:
- Only files intended for browser consumption are placed in `public_html`.
- Business logic, configuration, `.env`, error logs, and database schema files remain in a private directory **one level above web root**, making them physically unreachable by any HTTP request.

---

### Two-Tier Directory Layout Blueprint

```
/home/cpanelusername/
│
├── parijat_app/                                <--- PRIVATE (Above Web Root, 0% Web Accessible)
│   ├── .env                                   <--- Sensitive credentials (chmod 600)
│   ├── .env.example
│   ├── database_master.sql                    <--- Master database schema
│   ├── logs/
│   │   └── php_errors.log                     <--- Private error logs
│   └── includes/
│       ├── env.php                            <--- Environment parser
│       ├── config.php                         <--- PDO database connection
│       ├── bootstrap.php                      <--- Core path & header bootstrap
│       ├── router.php                         <--- Front Controller router
│       ├── email.php                          <--- PHPMailer wrapper
│       ├── PHPMailer/                         <--- SMTP library
│       ├── header.php                         <--- Frontend header
│       └── footer.php                         <--- Frontend footer
│
└── public_html/                               <--- PUBLIC WEB ROOT (DocumentRoot)
    ├── index.php                              <--- FRONT CONTROLLER Entry Point
    ├── about.php                              <--- Public pages (or views)
    ├── accomodation.php
    ├── banquet.php
    ├── dining.php
    ├── gallery.php
    ├── rules.php
    ├── contact.php
    ├── booking.php
    ├── book_process.php
    ├── contact_process.php
    ├── confirmation.php
    ├── 404.php
    ├── .htaccess                              <--- Security headers & URL rewrite
    ├── robots.txt
    ├── sitemap.xml
    ├── admin/                                 <--- Admin portal
    │   ├── index.php
    │   ├── login.php
    │   ├── bookings.php
    │   ├── room_categories.php
    │   ├── room_rates.php
    │   └── ...
    ├── assets/                                <--- Uploads, custom CSS & JS
    │   └── uploads/                           <--- Image upload destination (writable)
    ├── css/                                   <--- Stylesheets
    ├── js/                                    <--- JavaScript libraries
    ├── fonts/                                 <--- Web fonts
    ├── image/                                 <--- Resort static images
    └── vendors/                               <--- Frontend vendor plugins
```

---

### Step-by-Step Migration to `public_html`

#### Method 1: Using SSH Terminal (Fastest)
```bash
# 1. Navigate to your cPanel home folder
cd /home/cpanelusername

# 2. Create the private application directory
mkdir -p parijat_app/logs

# 3. Move private files into parijat_app
mv includes parijat_app/
mv .env parijat_app/
mv database_master.sql parijat_app/
mv database.sql parijat_app/
mv .git parijat_app/ 2>/dev/null || true

# 4. Move public files into public_html
cp -r * public_html/ 2>/dev/null || true

# 5. Set proper security permissions
chmod 600 parijat_app/.env
chmod -R 755 parijat_app/
chmod -R 755 public_html/
chmod -R 775 public_html/assets/uploads/
```

#### Method 2: Using cPanel File Manager (GUI)
1. Log in to cPanel and open **File Manager**.
2. In your home directory (`/home/cpanelusername/`):
   - Click **+ Folder** and name it `parijat_app`.
3. Select and move the following items into `/home/cpanelusername/parijat_app/`:
   - `includes/`
   - `.env`
   - `.env.example`
   - `database_master.sql`
   - `database.sql`
4. Move all remaining public web assets and pages (`index.php`, `about.php`, `admin/`, `assets/`, `css/`, `js/`, `image/`, `fonts/`, `vendors/`, `.htaccess`, `robots.txt`, `sitemap.xml`) into `/home/cpanelusername/public_html/`.

---

### Front Controller Path Bridging

Our zero-dependency `.env` loader (`includes/env.php`) was specifically designed with auto-detecting path fallbacks:
```php
$candidates = [
    __DIR__ . '/../.env',                      // Subdomain layout
    __DIR__ . '/../../.env',                   // Two-tier public_html layout
    dirname(__DIR__, 2) . '/parijat_app/.env', // Dedicated private app folder
    $_SERVER['DOCUMENT_ROOT'] . '/../.env',    // cPanel above webroot
    $_SERVER['DOCUMENT_ROOT'] . '/.env'
];
```
This means whether you run in a flat folder or a separated `public_html` folder, **no manual code alterations are required**.

For files inside `public_html/` requiring backend includes:
- If `includes/` is placed in `parijat_app/includes/`:
  Update the include line in `public_html/includes/config.php` (or a bridge file in `public_html/includes/config.php`):
  ```php
  <?php
  // public_html/includes/config.php bridge
  if (file_exists(__DIR__ . '/../../parijat_app/includes/config.php')) {
      require_once __DIR__ . '/../../parijat_app/includes/config.php';
  } else {
      require_once __DIR__ . '/config.php';
  }
  ```

---

### File & Folder Permission Matrix

| Item | Recommended Permission | Purpose |
| :--- | :--- | :--- |
| **All Directories** (`/`, `admin/`, `css/`, etc.) | `0755` (`drwxr-xr-x`) | Standard web execution & navigation |
| **Standard PHP & Static Files** (`index.php`, `.css`, `.js`) | `0644` (`-rw-r--r--`) | Readable by web server, writable by owner only |
| **Environment File (`.env`)** | `0600` (`-rw-------`) or `0400` | Strictly readable ONLY by the hosting user account |
| **Upload Directory (`assets/uploads/`)** | `0755` or `0775` | Required for admin room/gallery image uploads |
| **Uploads `.htaccess` (`assets/uploads/.htaccess`)** | `0644` | Enforces execution blocking (`php_flag engine off`) |

---

## 4. Part 3: Security Hardening & Troubleshooting

### Security Hardening Checklist
- [x] **`APP_DEBUG=false` in Production:** Hides raw PHP errors and stack traces from visitors.
- [x] **Strict Prepared Statements:** `PDO::ATTR_EMULATE_PREPARES => false` prevents SQL injection.
- [x] **CSRF Token Validation:** Synchronizer token validated on all booking, contact, and admin POST forms.
- [x] **Session Hardening:** `session.cookie_httponly = 1`, `session.cookie_secure = 1` over HTTPS, `samesite = Lax`.
- [x] **Brute Force Throttling:** 5 failed admin login attempts locks account for 5 minutes with randomized timing delay.
- [x] **Upload MIME Inspection:** Image uploads verified via `finfo_file(FILEINFO_MIME_TYPE)` rather than client file extensions.
- [x] **HTTP Security Headers:** `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy`.

---

### Common Troubleshooting Scenarios

#### 1. "HTTP 500 Internal Server Error"
- **Cause:** Typo in `.htaccess` or missing PHP module (`pdo_mysql`).
- **Fix:** Temporarily rename `.htaccess` to `.htaccess.bak`. If the error clears, check Apache `error_log` for unsupported directives (such as missing `mod_headers` or `mod_rewrite`).

#### 2. "Database connection error"
- **Cause:** Incorrect DB credentials in `.env`.
- **Fix:** Verify `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS`. In cPanel, the host is almost always `localhost` or `127.0.0.1`. Ensure the MySQL user is assigned to the database with **ALL PRIVILEGES**.

#### 3. "Clean URLs show 404 Not Found" (e.g., `/about` or `/contact`)
- **Cause:** `mod_rewrite` is disabled or `AllowOverride All` is not permitted.
- **Fix:** In Apache configuration, set `AllowOverride All` for the document root directory and ensure `RewriteEngine On` is enabled in `.htaccess`.

#### 4. "Failed to upload image in Admin Panel"
- **Cause:** Permission denied on `assets/uploads/` directory.
- **Fix:** Change folder permissions to `755` or `775`:
  `chmod 755 assets/uploads/`
  Ensure PHP `upload_max_filesize` and `post_max_size` in cPanel **Select PHP Version** &rarr; **Options** are set to at least `16M`.
