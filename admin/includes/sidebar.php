<?php
// admin/includes/sidebar.php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar-wrapper" id="sidebar">
    <!-- Brand Logo & Tagline -->
    <div class="pr-sidebar-header">
        <a href="index.php" class="pr-sidebar-brand">
            <div class="pr-logo-icon">
                <i class="bi bi-flower3"></i>
            </div>
            <div class="pr-brand-text">
                <div class="pr-brand-title">Parijat Resort</div>
                <div class="pr-brand-tagline">Stay &middot; Relax &middot; Reconnect</div>
            </div>
        </a>
    </div>

    <!-- Navigation Menu matching Reference Design -->
    <div class="flex-grow-1 overflow-y-auto pr-sidebar-scroll" style="scrollbar-width: none;">
        <ul class="pr-sidebar-menu">
            <li class="pr-menu-item">
                <a href="index.php" class="pr-menu-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Overview</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="bookings.php" class="pr-menu-link <?php echo $currentPage == 'bookings.php' ? 'active' : ''; ?>">
                    <i class="bi bi-calendar-check"></i>
                    <span>Bookings</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="room_categories.php" class="pr-menu-link <?php echo $currentPage == 'room_categories.php' ? 'active' : ''; ?>">
                    <i class="bi bi-door-open"></i>
                    <span>Room Categories (19)</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="room_rates.php" class="pr-menu-link <?php echo $currentPage == 'room_rates.php' ? 'active' : ''; ?>">
                    <i class="bi bi-tag"></i>
                    <span>Room Rates</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="room_images.php" class="pr-menu-link <?php echo $currentPage == 'room_images.php' ? 'active' : ''; ?>">
                    <i class="bi bi-images"></i>
                    <span>Room Images</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="amenities.php" class="pr-menu-link <?php echo $currentPage == 'amenities.php' ? 'active' : ''; ?>">
                    <i class="bi bi-star"></i>
                    <span>Amenities</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="restaurant.php" class="pr-menu-link <?php echo $currentPage == 'restaurant.php' ? 'active' : ''; ?>">
                    <i class="bi bi-cup-hot"></i>
                    <span>Restaurant Menu</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="guests.php" class="pr-menu-link <?php echo $currentPage == 'guests.php' ? 'active' : ''; ?>">
                    <i class="bi bi-people"></i>
                    <span>Guests</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="payments.php" class="pr-menu-link <?php echo $currentPage == 'payments.php' ? 'active' : ''; ?>">
                    <i class="bi bi-credit-card"></i>
                    <span>Payments</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="reports.php" class="pr-menu-link <?php echo $currentPage == 'reports.php' ? 'active' : ''; ?>">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Reports</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="contacts.php" class="pr-menu-link <?php echo $currentPage == 'contacts.php' ? 'active' : ''; ?>">
                    <i class="bi bi-envelope"></i>
                    <span>Messages</span>
                    <span class="pr-badge-count">3</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="banquet.php" class="pr-menu-link <?php echo $currentPage == 'banquet.php' ? 'active' : ''; ?>">
                    <i class="bi bi-buildings"></i>
                    <span>Banquet & Hall</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="policies.php" class="pr-menu-link <?php echo $currentPage == 'policies.php' ? 'active' : ''; ?>">
                    <i class="bi bi-shield-check"></i>
                    <span>Rules & Policies</span>
                </a>
            </li>

            <li class="pr-menu-item">
                <a href="site_settings.php" class="pr-menu-link <?php echo in_array($currentPage, ['site_settings.php', 'seo_settings.php']) ? 'active' : ''; ?>">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Sidebar Bottom Tagline & Art -->
    <div class="pr-sidebar-footer">
        <div class="pr-footer-tagline-wrap">
            <div class="pr-luxury-title">Nature's Luxury</div>
            <div class="pr-luxury-subtitle">At Parijat Resort</div>
        </div>
        <!-- Subtle leaf svg pattern background -->
        <svg class="pr-leaf-bg" viewBox="0 0 200 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 70C30 50 60 45 90 60C120 75 160 55 190 30" stroke="#4ade80" stroke-width="2" stroke-linecap="round"/>
            <path d="M30 65C45 40 70 35 95 45" stroke="#4ade80" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M120 65C135 45 165 40 185 50" stroke="#4ade80" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
    </div>
</div>
