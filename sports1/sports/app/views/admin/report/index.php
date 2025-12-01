<style>
    /* Report-specific styles */
    .report-card {
        background: #ffffff;
        border-radius: 10px;
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        transition: transform .2s, box-shadow .2s;
    }
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .report-card-label { font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: 600; }
    .report-card-value { font-size: 28px; font-weight: 700; margin-top: 8px; }
    .report-card-sub { font-size: 13px; color: #9ca3af; margin-top: 8px; }
    .chart-box {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .chart-title { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 16px; }
</style>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <!-- Card 1: Tổng sản phẩm -->
    <div class="report-card">
        <div class="report-card-label">Tổng sản phẩm</div>
        <div class="report-card-value" style="color: #2563eb;">
            <?php echo intval($productStats['total_products'] ?? 0); ?>
        </div>
        <div class="report-card-sub">
            Tồn kho: <strong style="color: #10b981;"><?php echo intval($productStats['total_stock'] ?? 0); ?></strong> sản phẩm
        </div>
    </div>

    <!-- Card 2: Sản phẩm đã bán -->
    <div class="report-card">
        <div class="report-card-label">Sản phẩm đã bán</div>
        <div class="report-card-value" style="color: #f97316;">
            <?php echo intval($productStats['total_sold'] ?? 0); ?>
        </div>
        <div class="report-card-sub">
            Tổng số lượng bán ra
        </div>
    </div>

    <!-- Card 3: Tổng số đơn hàng -->
    <div class="report-card">
        <div class="report-card-label">Tổng số đơn hàng</div>
        <div class="report-card-value" style="color: #a855f7;">
            <?php echo intval($orderSummary['total_orders'] ?? 0); ?>
        </div>
        <div class="report-card-sub">
            Hoàn thành: <strong style="color: #10b981;"><?php echo intval($orderSummary['completed'] ?? 0); ?></strong> · 
            Hủy: <strong style="color: #ef4444;"><?php echo intval($orderSummary['cancelled'] ?? 0); ?></strong>
        </div>
    </div>

    <!-- Card 4: Tổng doanh thu -->
    <div class="report-card">
        <div class="report-card-label">Tổng doanh thu</div>
        <div class="report-card-value" style="color: #06b6d4;">
            <?php 
            $revenue = floatval($orderSummary['total_revenue'] ?? 0);
            echo number_format($revenue, 0, ',', '.') . 'đ';
            ?>
        </div>
        <div class="report-card-sub">
            Tính từ tất cả đơn hàng hoàn thành
        </div>
    </div>
</div>

<!-- Chart Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 16px;">
    <!-- Chart 1: Revenue Trend -->
    <div class="chart-box" style="grid-column: 1 / -1;">
        <div class="chart-title">Xu hướng Doanh thu (6 tháng gần nhất)</div>
        <canvas id="revenueTrendChart" style="max-height: 300px; width: 100%;"></canvas>
    </div>

    <!-- Chart 2: Product Stock vs Sold -->
    <div class="chart-box">
        <div class="chart-title">Tỷ lệ Tồn kho & Đã bán</div>
        <canvas id="stockSoldChart" style="max-height: 250px; width: 100%;"></canvas>
    </div>
    
    <!-- Chart 3: Order Status Distribution -->
    <div class="chart-box">
        <div class="chart-title">Phân phối Trạng thái Đơn hàng</div>
        <canvas id="orderStatusChart" style="max-height: 250px; width: 100%;"></canvas>
    </div>

    <!-- Chart 4: Top 5 Best-selling Products -->
    <div class="chart-box" style="grid-column: 1 / -1;">
        <div class="chart-title">Top 5 Sản phẩm bán chạy</div>
        <canvas id="topProductsChart" style="max-height: 300px; width: 100%;"></canvas>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
        // Dữ liệu từ database (truyền từ PHP)
        const realData = {
            // Dữ liệu doanh thu theo tháng từ database
            monthlyRevenue: <?php 
                $data = [];
                foreach ($monthlyRevenue as $item) {
                    $data[] = [
                        'month' => $item['month'],
                        'revenue' => floatval($item['revenue'])
                    ];
                }
                echo json_encode($data);
            ?>,
            
            // Dữ liệu tồn kho và đã bán
            productDistribution: {
                stock: <?php echo intval($productStats['total_stock'] ?? 0); ?>,
                sold: <?php echo intval($productStats['total_sold'] ?? 0); ?>,
            },
            
            // Dữ liệu phân phối trạng thái đơn hàng
            orderDistribution: {
                completed: <?php echo intval($orderSummary['completed'] ?? 0); ?>,
                cancelled: <?php echo intval($orderSummary['cancelled'] ?? 0); ?>,
                pending: <?php echo intval($orderSummary['pending'] ?? 0); ?>,
            },
            
            // Dữ liệu top sản phẩm bán chạy
            topProducts: <?php 
                $products = [];
                foreach ($topProducts as $item) {
                    $products[] = [
                        'name' => $item['name'],
                        'quantity' => intval($item['quantity'])
                    ];
                }
                echo json_encode($products);
            ?>
        };

        // Hàm định dạng số tiền (ví dụ: 150,000,000đ)
        const formatCurrency = (number) => {
            if (typeof number !== 'number') return number;
            return number.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
        };
        
        // --- 1. Biểu đồ Xu hướng Doanh thu (Line Chart) ---
        const renderRevenueTrendChart = () => {
            const ctx = document.getElementById('revenueTrendChart').getContext('2d');
            
            const data = realData.monthlyRevenue.length > 0 ? realData.monthlyRevenue : [
                { month: 'Chưa có dữ liệu', revenue: 0 }
            ];
            const labels = data.map(item => item.month);
            const revenues = data.map(item => item.revenue);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh thu',
                        data: revenues,
                        borderColor: '#06b6d4', // Teal 500
                        backgroundColor: 'rgba(6, 182, 212, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        title: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Doanh thu (VND)'
                            },
                            ticks: {
                                callback: function(value, index, ticks) {
                                    // Hiển thị ở đơn vị triệu đồng cho gọn
                                    return (value / 1000000).toLocaleString() + ' Tr';
                                }
                            }
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return formatCurrency(tooltipItem.raw);
                            }
                        }
                    }
                }
            });
        };

        // --- 2. Biểu đồ Tồn kho & Đã bán (Doughnut Chart) ---
        const renderStockSoldChart = () => {
            const ctx = document.getElementById('stockSoldChart').getContext('2d');
            const data = realData.productDistribution;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Tồn kho', 'Đã bán'],
                    datasets: [{
                        data: [data.stock, data.sold],
                        backgroundColor: ['#10b981', '#f97316'], // Green and Orange
                        hoverOffset: 10,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                            }
                        },
                        title: { display: false }
                    }
                }
            });
        };

        // --- 3. Biểu đồ Phân phối Trạng thái Đơn hàng (Pie Chart) ---
        const renderOrderStatusChart = () => {
            const ctx = document.getElementById('orderStatusChart').getContext('2d');
            const data = realData.orderDistribution;

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Hoàn thành', 'Đã hủy', 'Đang xử lý'],
                    datasets: [{
                        data: [data.completed, data.cancelled, data.pending],
                        backgroundColor: ['#22c55e', '#ef4444', '#f59e0b'], // Green, Red, Amber
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                            }
                        },
                        title: { display: false }
                    }
                }
            });
        };

        // --- 4. Biểu đồ Top Sản phẩm bán chạy (Bar Chart) ---
        const renderTopProductsChart = () => {
            const ctx = document.getElementById('topProductsChart').getContext('2d');
            const data = realData.topProducts.length > 0 ? realData.topProducts : [{name: 'Chưa có dữ liệu', quantity: 0}];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(p => p.name),
                    datasets: [{
                        label: 'Số lượng bán ra',
                        data: data.map(p => p.quantity),
                        backgroundColor: [
                            '#3b82f6', // Blue
                            '#8b5cf6', // Violet
                            '#f43f5e', // Rose
                            '#22c55e', // Green
                            '#f59e0b'  // Amber
                        ],
                        borderColor: '#1f2937',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Biểu đồ cột ngang
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số lượng'
                            }
                        }
                    }
                }
            });
        };

        // Chạy các hàm vẽ biểu đồ khi trang tải xong
        document.addEventListener('DOMContentLoaded', () => {
            renderRevenueTrendChart();
            renderStockSoldChart();
            renderOrderStatusChart();
            renderTopProductsChart();
        });
    </script>