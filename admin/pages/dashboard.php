<?php
ob_start();
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/class.user.php';

if(!isset($_SESSION['admin_logged_in'])){
    header('Location: ../login.php');
    exit;
}

$database = new Database();
$db = $database->db_connection();
$user = new User();

// --- Fetch total revenue ---
$total_revenue = $db->query("SELECT COALESCE(SUM(total_price), 0) as total FROM bookings WHERE status = 'complete'")->fetch(PDO::FETCH_ASSOC)['total'];

// --- Fetch total customers ---
$total_customers = $db->query("SELECT COUNT(DISTINCT user_id) as total FROM bookings WHERE status = 'complete'")->fetch(PDO::FETCH_ASSOC)['total'];

// --- Fetch top booking rooms ---
$top_rooms = $db->query("
    SELECT r.name, COUNT(b.id) as booking_count 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    WHERE b.status = 'complete' 
    GROUP BY b.room_id 
    ORDER BY booking_count DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// --- Fetch monthly revenue ---
$current_year = date('Y');
$monthly_revenue = $db->query("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        COALESCE(SUM(total_price), 0) as revenue
    FROM bookings 
    WHERE status = 'complete' AND YEAR(created_at) = $current_year
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month ASC
")->fetchAll(PDO::FETCH_ASSOC);

// --- Fill missing months with 0 revenue ---
$project_start = 4; // April = 4 (project started month)
$months = [];
$revenues = [];

for ($m = $project_start; $m <= 12; $m++) {
    $month_key = $current_year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
    $months[$month_key] = date('M', strtotime($month_key . '-01'));
    $revenues[$month_key] = 0;
}

foreach ($monthly_revenue as $row) {
    $revenues[$row['month']] = (float)$row['revenue'];
}

// --- Fetch daily revenue for current month ---
$current_month = date('Y-m');
$days_in_month = date('t'); // Total days in current month
$daily_revenue = $db->query("
    SELECT 
        DATE(created_at) as day,
        COALESCE(SUM(total_price), 0) as revenue
    FROM bookings 
    WHERE status = 'complete' AND DATE_FORMAT(created_at, '%Y-%m') = '$current_month'
    GROUP BY DATE(created_at)
    ORDER BY day ASC
")->fetchAll(PDO::FETCH_ASSOC);

// --- Fill missing days with 0 revenue ---
$days = [];
$daily_revenues = [];

for ($d = 1; $d <= $days_in_month; $d++) {
    $day_key = $current_month . '-' . str_pad($d, 2, '0', STR_PAD_LEFT);
    $days[$day_key] = $d;
    $daily_revenues[$day_key] = 0;
}

foreach ($daily_revenue as $row) {
    $daily_revenues[$row['day']] = (float)$row['revenue'];
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="row mt-4">
    <div class="col-md-12">
        <h3>Sales Report</h3>
        <div class="row">
            <!-- Total Revenue -->
            <div class="col-md-4 mb-3">
                <div class="card radius-10 border-0 border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="">
                                <p class="mb-1">Total Revenue</p>
                                <h4 class="mb-0 text-primary">৳<?= number_format($total_revenue, 2) ?></h4>
                            </div>
                            <div class="ms-auto widget-icon bg-primary text-white">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4.5px;">
                            <div class="progress-bar" role="progressbar" style="width: 75%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Total Customers -->
            <div class="col-md-4 mb-3">
                <div class="card radius-10 border-0 border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="">
                                <p class="mb-1">Total Customers</p>
                                <h4 class="mb-0 text-success"><?= $total_customers ?></h4>
                            </div>
                            <div class="ms-auto widget-icon bg-success text-white">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4.5px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 75%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Total Bookings -->
            <div class="col-md-4 mb-3">
                <div class="card radius-10 border-0 border-start border-warning border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="">
                                <p class="mb-1">Total Bookings</p>
                                <?php $total_bookings = $db->query("SELECT COUNT(*) as total FROM bookings WHERE status = 'complete'")->fetch(PDO::FETCH_ASSOC)['total']; ?>
                                <h4 class="mb-0 text-warning"><?= $total_bookings ?></h4>
                            </div>
                            <div class="ms-auto widget-icon bg-warning text-white">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4.5px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 75%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Bar Chart -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card radius-10 border-0">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Monthly Revenue (<?php echo $current_year; ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div id="monthlyRevenueChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Revenue Line Chart and Top Booking Rooms -->
        <div class="row mt-4">
            <!-- Daily Revenue Line Chart -->
            <div class="col-md-8">
                <div class="card radius-10 border-0">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Daily Revenue (<?php echo date('F Y'); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div id="dailyRevenueChart"></div>
                    </div>
                </div>
            </div>
            <!-- Top Booking Rooms Table -->
            <div class="col-md-4">
                <div class="card radius-10 border-0">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Top Booking Rooms</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Room Name</th>
                                        <th>Booking Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($top_rooms)): ?>
                                        <tr>
                                            <td colspan="2" class="text-center">No data available</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($top_rooms as $room): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($room['name']) ?></td>
                                                <td><?= $room['booking_count'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Monthly Revenue Bar Chart
    var months = <?php echo json_encode(array_values($months)); ?>;
    var revenues = <?php echo json_encode(array_values($revenues)); ?>;

    var monthlyOptions = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        series: [{
            name: 'Revenue (BDT)',
            data: revenues
        }],
        xaxis: {
            categories: months,
            title: {
                text: 'Month'
            }
        },
        yaxis: {
            title: {
                text: 'Revenue (BDT)'
            },
            min: 0,
            labels: {
                formatter: function (value) {
                    return '৳' + value.toLocaleString('en-US', {minimumFractionDigits: 2});
                }
            }
        },
        title: {
            text: 'Monthly Revenue Overview',
            align: 'center'
        },
        colors: ['#007bff'],
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return '৳' + val.toLocaleString('en-US', {minimumFractionDigits: 2});
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ['#304758']
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: false
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return '৳' + val.toLocaleString('en-US', {minimumFractionDigits: 2});
                }
            }
        }
    };

    var monthlyChart = new ApexCharts(document.querySelector("#monthlyRevenueChart"), monthlyOptions);
    monthlyChart.render();

    // Daily Revenue Line Chart
    var days = <?php echo json_encode(array_values($days)); ?>;
    var dailyRevenues = <?php echo json_encode(array_values($daily_revenues)); ?>;

    var dailyOptions = {
        chart: {
            type: 'line',
            height: 350,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        series: [{
            name: 'Revenue (BDT)',
            data: dailyRevenues
        }],
        xaxis: {
            categories: days,
            title: {
                text: 'Day'
            },
            type: 'category',
            tickAmount: days.length > 10 ? 10 : undefined, // Limit ticks for readability
            labels: {
                rotate: -45,
                trim: true
            }
        },
        yaxis: {
            title: {
                text: 'Revenue (BDT)'
            },
            min: 0, // Ensure y-axis starts at 0
            forceNiceScale: true, // Better scaling for sparse data
            labels: {
                formatter: function (value) {
                    return '৳' + value.toLocaleString('en-US', {minimumFractionDigits: 2});
                }
            }
        },
        title: {
            text: 'Daily Revenue Overview',
            align: 'center'
        },
        colors: ['#28a745'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 5
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return '৳' + val.toLocaleString('en-US', {minimumFractionDigits: 2});
                }
            }
        },
        noData: {
            text: 'No revenue data available for this month',
            align: 'center',
            verticalAlign: 'middle',
            offsetX: 0,
            offsetY: 0,
            style: {
                color: '#000000',
                fontSize: '14px'
            }
        }
    };

    var dailyChart = new ApexCharts(document.querySelector("#dailyRevenueChart"), dailyOptions);
    dailyChart.render();
</script>

<?php ob_end_flush(); ?>
