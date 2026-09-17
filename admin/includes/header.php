<?php
// admin/includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Ensure user is logged in
require_once __DIR__.'/../auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parijat Resort Admin</title>
    
    <link rel="icon" type="image/png" href="assets/images/favicon.ico">
    
    <!-- Local Third-Party Libraries -->
    <link rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/libs/bootstrap-icons/bootstrap-icons.css">
    
    <!-- Main Design System & Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- Parijat Custom Luxury Dashboard Theme -->
    <link rel="stylesheet" href="assets/css/parijat_dashboard.css">
    <!-- Chart.js for Dashboard Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- Sidebar Component -->
    <?php include __DIR__ . '/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-wrapper">

        <!-- Top Navbar Component matching reference design -->
        <header class="pr-top-navbar">
            <div class="d-flex align-items-center">
                <!-- Mobile sidebar toggle -->
                <button class="sidebar-toggle-btn me-3 d-xl-none" id="sidebar-toggle" aria-label="Toggle Navigation">
                    <i class="bi bi-list fs-4"></i>
                </button>
            </div>

            <!-- Search Bar Pill -->
            <div class="pr-search-container">
                <i class="bi bi-search pr-search-icon"></i>
                <input type="text" class="pr-search-input" placeholder="Search bookings, guests, rooms...">
            </div>

            <!-- Right Navbar: Notification Bell & Admin Profile -->
            <div class="pr-nav-right">
                <!-- Notifications Dropdown -->
                <div class="dropdown me-2 me-sm-3">
                    <button class="pr-notify-btn" type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications" style="border: none; background: transparent;">
                        <i class="bi bi-bell"></i>
                        <span class="pr-notify-badge">3</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-0" aria-labelledby="notifDropdown" style="width: 300px; border-radius: 8px; overflow: hidden;">
                        <li class="dropdown-header bg-light py-2 fw-bold text-dark border-bottom">
                            Notifications (3)
                        </li>
                        <li>
                            <a class="dropdown-item py-3 border-bottom" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div>
                                        <div class="text-dark fw-bold" style="font-size: 0.85rem;">New Booking Enquiry</div>
                                        <div class="text-muted" style="font-size: 0.8rem;">From Rahul Gupta</div>
                                        <div class="text-muted mt-1" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>10 mins ago</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-3 border-bottom" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <div>
                                        <div class="text-dark fw-bold" style="font-size: 0.85rem;">Booking Confirmed</div>
                                        <div class="text-muted" style="font-size: 0.8rem;">Deluxe Cottage</div>
                                        <div class="text-muted mt-1" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>1 hour ago</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-3" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                    <div>
                                        <div class="text-dark fw-bold" style="font-size: 0.85rem;">Low Inventory</div>
                                        <div class="text-muted" style="font-size: 0.8rem;">Water bottles running low</div>
                                        <div class="text-muted mt-1" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>Yesterday</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="text-center py-2 bg-light border-top">
                            <a href="#" class="text-decoration-none text-primary fw-bold" style="font-size: 0.85rem;">Mark all as read</a>
                        </li>
                    </ul>
                </div>

                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="pr-user-profile dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop" alt="Admin" class="pr-user-avatar">
                        <div class="pr-user-info d-none d-sm-block">
                            <div class="pr-user-name">Admin</div>
                            <div class="pr-user-role">Resort Manager</div>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="profileDropdown">
                        <li class="dropdown-header">
                            <div class="fw-bold text-dark">Admin (Resort Manager)</div>
                            <small class="text-muted">Parijat Resort Bolpur</small>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>My Profile</a></li>
                        <li><a class="dropdown-item" href="site_settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </header>
        <!-- END: Top Navbar -->
        
        <!-- Main Body Wrapper -->
        <div class="dashboard-inner-content">
