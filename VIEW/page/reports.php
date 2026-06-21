<?php
define('BASE_PATH', __DIR__);
define('BASE_URL', '../');
session_start();

if (!isset($_SESSION['account_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}
require_once '../../MODEL/connect.php';

require_once '../../MODEL/Account.php';
require_once '../../MODEL/Order.php';
require_once '../../MODEL/Product.php';

$accountModel = new Account($conn);
$orderModel   = new Order($conn);
$productModel = new Product($conn);
$revenue = $orderModel->getTotalRevenue();

$totalOrders = $orderModel->countAll();

$newCustomers = $accountModel->countNewCustomersThisMonth();

$completionRate = $orderModel->getCompletionRate();
$top_products = $productModel->getTopSellingProducts(5);
$topCustomers = $accountModel->getTopCustomers(5);

/* Doanh thu theo tháng */
$monthlyRevenue = array_fill(0, 12, 0);
$monthlyOrders  = array_fill(0, 12, 0);

$revenueResult = $orderModel->getRevenueByMonth();

while ($row = $revenueResult->fetch_assoc()) {
    $index = (int)$row['month_num'] - 1;

    $monthlyRevenue[$index] =
        round($row['revenue'] / 1000000, 2);

    $monthlyOrders[$index] =
        (int)$row['total_orders'];
}

/* Trạng thái đơn hàng */
$statusData = [
    'completed'  => 0,
    'processing' => 0,
    'pending'    => 0,
    'cancelled'  => 0
];

$statusResult = $orderModel->getStatusStatistics();

while ($row = $statusResult->fetch_assoc()) {
    $statusData[$row['status']] = (int)$row['total'];
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Báo cáo – MHZ Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>style/reset.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/font.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/common.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/footer.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/reports.css?v=<?= time() ?>" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
</head>

<body>
    <div class="wrapper">

        <!-- ===== HEADER ===== -->
        <div class="admin-header">
            <a href="admin.php" class="logo">
                <h2>MHZ Admin</h2>
            </a>

            <nav class="admin-nav">
                <a href="admin.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="accounts.php"><i class="fas fa-users"></i> Tài khoản</a>
                <a href="products-admin.php"><i class="fas fa-gamepad"></i> Sản phẩm</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Đơn hàng</a>
                <a href="reports.php" class="active"><i class="fas fa-chart-line"></i> Báo cáo</a>
            </nav>

            <div class="admin-user">
                <?php if (!empty($_SESSION['avatar_url'])): ?>
                    <img src="<?= BASE_URL . 'assets/images/avatar/' . htmlspecialchars($_SESSION['avatar_url']) ?>" alt="Avatar">
                <?php else: ?>
                    <img src="<?= BASE_URL ?>assets/images/avatar/avatar.png" alt="Avatar">
                <?php endif; ?>
                <div class="user-info">
                    <span class="name"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                    <small>Administrator</small>
                </div>
                <a href="logout.php" class="logout-btn">Đăng xuất</a>
            </div>
        </div>

        <!-- ===== NỘI DUNG ===== -->
        <div class="reports-content">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div class="page-title">
                    <i class="fas fa-chart-line"></i>
                    Báo cáo &amp; Thống kê
                </div>
                <div class="header-right">
                    <select class="range-select" id="rangeSelect" onchange="updateRange(this.value)">
                        <option value="30">30 ngày gần đây</option>
                        <option value="7">7 ngày gần đây</option>
                        <option value="month">Tháng này</option>
                        <option value="year">Năm nay</option>
                        <option value="all">Tất cả</option>
                    </select>
                    <a href="reports-export.php" class="btn-export">
                        <i class="fas fa-download"></i> Xuất báo cáo
                    </a>
                </div>
            </div>

            <!-- STAT CARDS -->
            <div class="stats-row">
                <?php
                /*
             * Thay các số dưới đây bằng truy vấn DB thực, ví dụ:
             * $revenue = $conn->query("SELECT SUM(total) FROM orders WHERE status='completed'")->fetch_row()[0];
             */
                $stats = [
                    [
                        'label' => 'Doanh thu',
                        'value' => number_format($revenue, 0, ',', '.') . ' ₫',
                        'color' => 'purple'
                    ],
                    [
                        'label' => 'Đơn hàng',
                        'value' => number_format($totalOrders),
                        'color' => 'cyan'
                    ],
                    [
                        'label' => 'Khách hàng mới',
                        'value' => $newCustomers,
                        'color' => 'yellow'
                    ],
                    [
                        'label' => 'Tỉ lệ hoàn thành',
                        'value' => $completionRate . '%',
                        'color' => 'green'
                    ]
                ];
                foreach ($stats as $s): ?>
                    <div class="stat-card">
                        <div class="stat-label"><?= $s['label'] ?></div>
                        <div class="stat-val <?= $s['color'] ?>"><?= $s['value'] ?></div>
                        <div class="stat-trend">
                            Dữ liệu cập nhật theo thời gian thực
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- BIỂU ĐỒ DOANH THU + TRẠNG THÁI -->
            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-title">Doanh thu theo tháng</div>
                    <div class="chart-sub">12 tháng gần nhất – đơn vị: triệu đồng</div>
                    <div class="chart-legend">
                        <div class="legend-item"><span class="leg-dot" style="background:#8b5cf6"></span>Doanh thu</div>
                        <div class="legend-item"><span class="leg-dot" style="background:#22d3ee"></span>Đơn hàng</div>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="revenueChart" role="img" aria-label="Biểu đồ doanh thu và đơn hàng theo 12 tháng">
                            Biểu đồ doanh thu và số đơn hàng theo từng tháng trong năm.
                        </canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-title">Trạng thái đơn hàng</div>
                    <div class="chart-sub">Phân bố theo tỉ lệ</div>
                    <div class="chart-legend">
                        <div class="legend-item"><span class="leg-dot" style="background:#22d3ee"></span>Hoàn thành 77%</div>
                        <div class="legend-item"><span class="leg-dot" style="background:#8b5cf6"></span>Xử lý 16%</div>
                        <div class="legend-item"><span class="leg-dot" style="background:#f59e0b"></span>Chờ 4%</div>
                        <div class="legend-item"><span class="leg-dot" style="background:#ef4444"></span>Huỷ 3%</div>
                    </div>
                    <div class="chart-wrap" style="height:200px">
                        <canvas id="statusChart" role="img" aria-label="Biểu đồ tròn tỉ lệ trạng thái đơn hàng">
                            Hoàn thành 77%, Đang xử lý 16%, Chờ xác nhận 4%, Đã huỷ 3%.
                        </canvas>
                    </div>
                </div>
            </div>

            <!-- BẢNG TOP SẢN PHẨM + TOP KHÁCH HÀNG -->
            <div class="bottom-row">

                <!-- Top sản phẩm -->
                <div class="table-card">
                    <div class="chart-title">Top sản phẩm bán chạy</div>
                    <div class="chart-sub">Theo doanh thu – tháng này</div>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sản phẩm</th>
                                <th>Doanh thu</th>
                                <th>Tỉ lệ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rank = 1;

                            while ($p = $top_products->fetch_assoc()):
                            ?>
                                <tr>
                                    <td class="rank">
                                        <?= str_pad($rank++, 2, '0', STR_PAD_LEFT) ?>
                                    </td>

                                    <td>
                                        <div class="prod-name">
                                            <?= htmlspecialchars($p['product_name']) ?>
                                        </div>

                                        <div class="prod-cat">
                                            <?= htmlspecialchars($p['category_name'] ?? '') ?>
                                        </div>
                                    </td>

                                    <td class="rev">
                                        <?= number_format($p['revenue'], 0, ',', '.') ?> ₫
                                    </td>

                                    <td>
                                        <div class="bar-wrap">
                                            <div class="bar-fill" style="width:100%"></div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Top khách hàng -->
                <div class="table-card">
                    <div class="chart-title">Khách hàng chi tiêu nhiều nhất</div>
                    <div class="chart-sub">Top 5 – tháng này</div>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Khách hàng</th>
                                <th>Chi tiêu</th>
                                <th>Đơn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rank = 1;

                            while ($c = $topCustomers->fetch_assoc()):
                            ?>
                                <tr>
                                    <td class="rank">
                                        <?= str_pad($rank++, 2, '0', STR_PAD_LEFT) ?>
                                    </td>

                                    <td>
                                        <div class="prod-name">
                                            <?= htmlspecialchars($c['username']) ?>
                                        </div>

                                        <div class="prod-cat">
                                            <?= htmlspecialchars($c['email']) ?>
                                        </div>
                                    </td>

                                    <td class="rev">
                                        <?= number_format($c['total_spent'], 0, ',', '.') ?> ₫
                                    </td>

                                    <td class="orders-count">
                                        <?= $c['total_orders'] ?> đơn
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            </div><!-- /bottom-row -->
        </div><!-- /reports-content -->

        <?php require '../includes/footer.php'; ?>
    </div><!-- /wrapper -->

    <!-- ===== CHART.JS ===== -->
    <script>
        const months = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];

        /* Dữ liệu mẫu – thay bằng JSON từ PHP/DB */
        const revenueData =
            <?= json_encode($monthlyRevenue) ?>;

        const ordersData =
            <?= json_encode($monthlyOrders) ?>;

        /* Biểu đồ doanh thu */
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                        label: 'Doanh thu (M)',
                        data: revenueData,
                        backgroundColor: 'rgba(139,92,246,.75)',
                        borderRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Đơn hàng',
                        data: ordersData,
                        type: 'line',
                        borderColor: '#22d3ee',
                        backgroundColor: 'transparent',
                        pointBackgroundColor: '#22d3ee',
                        pointRadius: 3,
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#475569',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(255,255,255,.04)'
                        }
                    },
                    y: {
                        position: 'left',
                        ticks: {
                            color: '#475569',
                            font: {
                                size: 11
                            },
                            callback: v => v + 'M'
                        },
                        grid: {
                            color: 'rgba(255,255,255,.04)'
                        }
                    },
                    y1: {
                        position: 'right',
                        ticks: {
                            color: '#22d3ee',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        /* Biểu đồ trạng thái */
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Hoàn thành', 'Đang xử lý', 'Chờ xác nhận', 'Đã huỷ'],
                datasets: [{
                    data: [
                        <?= $statusData['completed'] ?>,
                        <?= $statusData['processing'] ?>,
                        <?= $statusData['pending'] ?>,
                        <?= $statusData['cancelled'] ?>
                    ],
                    backgroundColor: ['#22d3ee', '#8b5cf6', '#f59e0b', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.label + ': ' + ctx.parsed + '%'
                        }
                    }
                }
            }
        });

        function updateRange(val) {
            /* Gửi request AJAX hoặc reload trang với param ?range=val */
            window.location.href = 'reports.php?range=' + val;
        }
    </script>
</body>

</html>