<?php
// admin/reports.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

// --- DATA AGGREGATION FOR REPORTS ---

// 1. Overall Stats
$stats = [
    'total_inquiries' => 0,
    'confirmed_bookings' => 0,
    'total_revenue' => 0,
    'total_guests' => 0
];

$stats['total_inquiries'] = $pdo->query("SELECT COUNT(*) FROM booking_inquiries")->fetchColumn();
$stats['confirmed_bookings'] = $pdo->query("SELECT COUNT(*) FROM booking_inquiries WHERE status = 'confirmed'")->fetchColumn();
$stats['total_revenue'] = $pdo->query("SELECT SUM(amount) FROM payments")->fetchColumn() ?: 0;

// Unique guests based on email/phone
$stats['total_guests'] = $pdo->query("SELECT COUNT(DISTINCT email, phone) FROM booking_inquiries")->fetchColumn();


// 2. Booking Status Breakdown (for Pie Chart)
$statusCounts = $pdo->query("
    SELECT status, COUNT(*) as count 
    FROM booking_inquiries 
    GROUP BY status
")->fetchAll(PDO::FETCH_ASSOC);

$statusLabels = [];
$statusData = [];
$statusColors = [];
foreach ($statusCounts as $row) {
    $statusLabels[] = ucfirst($row['status']);
    $statusData[] = $row['count'];
    if ($row['status'] == 'new') $statusColors[] = '#ef4444'; // Red
    elseif ($row['status'] == 'contacted') $statusColors[] = '#f59e0b'; // Yellow
    elseif ($row['status'] == 'confirmed') $statusColors[] = '#10b981'; // Green
    else $statusColors[] = '#6b7280'; // Gray
}


// 3. Last 7 Days Revenue (for Bar Chart)
// Using PHP to generate the last 7 dates to ensure zeroes are filled in if no payments occurred
$last7Days = [];
for ($i = 6; $i >= 0; $i--) {
    $last7Days[] = date('Y-m-d', strtotime("-$i days"));
}

$revenueStmt = $pdo->query("
    SELECT DATE(created_at) as pay_date, SUM(amount) as daily_total
    FROM payments
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
");
$revenueRows = $revenueStmt->fetchAll(PDO::FETCH_ASSOC);

$revenueMap = [];
foreach ($revenueRows as $r) {
    $revenueMap[$r['pay_date']] = (float)$r['daily_total'];
}

$revenueLabels = [];
$revenueData = [];
foreach ($last7Days as $date) {
    $revenueLabels[] = date('d M', strtotime($date));
    $revenueData[] = $revenueMap[$date] ?? 0;
}


// 4. Popular Room Categories
$popularRooms = $pdo->query("
    SELECT c.name, COUNT(b.id) as count
    FROM booking_inquiries b
    JOIN room_categories c ON b.category_id = c.id
    GROUP BY c.id
    ORDER BY count DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Analytics & Reports</h2>
        <p class="text-muted mb-0">Overview of your resort's performance</p>
    </div>
    <button class="btn btn-outline-primary fw-semibold" onclick="window.print()">
        <i class="bi bi-printer me-1"></i> Print Report
    </button>
</div>

<!-- Key Metrics Row -->
<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 pr-stats-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-muted mb-0">Total Revenue</h6>
                    <div class="pr-icon-circle bg-success bg-opacity-10 text-success">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <h3 class="mb-0 fw-bold">₹<?php echo number_format($stats['total_revenue']); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 pr-stats-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-muted mb-0">Confirmed Bookings</h6>
                    <div class="pr-icon-circle bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
                <h3 class="mb-0 fw-bold"><?php echo $stats['confirmed_bookings']; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 pr-stats-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-muted mb-0">Total Inquiries</h6>
                    <div class="pr-icon-circle bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-telephone-inbound"></i>
                    </div>
                </div>
                <h3 class="mb-0 fw-bold"><?php echo $stats['total_inquiries']; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 pr-stats-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-muted mb-0">Unique Guests</h6>
                    <div class="pr-icon-circle bg-info bg-opacity-10 text-info">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <h3 class="mb-0 fw-bold"><?php echo $stats['total_guests']; ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    
    <!-- Revenue Bar Chart -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up text-primary me-2"></i> Revenue (Last 7 Days)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Status Pie Chart -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pie-chart text-primary me-2"></i> Inquiry Status</h5>
            </div>
            <div class="card-body d-flex justify-content-center">
                <div style="width: 80%;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Popular Rooms Table -->
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-fire text-danger me-2"></i> Popular Room Categories</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Room Category</th>
                                <th class="text-center pe-3">Times Booked / Inquired</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($popularRooms as $pr): ?>
                            <tr>
                                <td class="ps-3 fw-semibold"><?php echo htmlspecialchars($pr['name']); ?></td>
                                <td class="text-center pe-3">
                                    <span class="badge bg-primary rounded-pill px-3"><?php echo $pr['count']; ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize Charts -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Revenue Bar Chart
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($revenueLabels); ?>,
            datasets: [{
                label: 'Revenue (₹)',
                data: <?php echo json_encode($revenueData); ?>,
                backgroundColor: 'rgba(74, 222, 128, 0.5)',
                borderColor: 'rgba(21, 128, 61, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 2. Status Pie Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($statusLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($statusData); ?>,
                backgroundColor: <?php echo json_encode($statusColors); ?>,
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            },
            cutout: '70%'
        }
    });

});
</script>

<?php require_once __DIR__.'/includes/footer.php'; ?>
