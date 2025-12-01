<?php
// $productStats: total_products, total_stock, total_sold
// $orderSummary: total_orders, total_revenue, completed, pending, cancelled
// $recentOrders: danh sách vài đơn hàng mới
?>
<h2>Dashboard admin</h2>
<p class="muted">
    Tổng quan hệ thống cửa hàng thể thao: số lượng sản phẩm, đơn hàng và doanh thu (dữ liệu lấy từ DB).
</p>

<div class="card-grid">
    <div class="card">
        <div class="card-label">Tổng sản phẩm</div>
        <div class="card-value">
            <?php echo (int)$productStats['total_products']; ?>
        </div>
        <div class="card-sub">
            Tồn kho hiện tại: <?php echo (int)$productStats['total_stock']; ?> sản phẩm
        </div>
    </div>

    <div class="card">
        <div class="card-label">Tổng số đơn hàng</div>
        <div class="card-value">
            <?php echo (int)$orderSummary['total_orders']; ?>
        </div>
        <div class="card-sub">
            Đã hoàn thành: <?php echo (int)$orderSummary['completed']; ?> đơn
        </div>
    </div>

    <div class="card">
        <div class="card-label">Sản phẩm đã bán</div>
        <div class="card-value">
            <?php echo (int)$productStats['total_sold']; ?>
        </div>
        <div class="card-sub">
            Tính theo trường <code>number_sold</code> trong bảng PRODUCT
        </div>
    </div>

    <div class="card">
        <div class="card-label">Tổng doanh thu</div>
        <div class="card-value">
            <?php echo number_format($orderSummary['total_revenue']); ?>đ
        </div>
        <div class="card-sub">
            Tính từ bảng ORDERS (cột <code>total_cost</code>)
        </div>
    </div>
</div>

<h3 style="font-size:16px; margin-bottom:6px;">Đơn hàng mới nhất</h3>
<p class="muted">Một vài đơn hàng gần đây để bạn theo dõi nhanh.</p>

<div style="overflow-x:auto;">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Khách hàng</th>
            <th>SĐT</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Thời gian tạo</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($recentOrders)): ?>
            <?php foreach ($recentOrders as $o): ?>
                <?php
                $status = $o['status'];
                if ($status === 'COMPLETED') {
                    $badgeClass = 'badge-success';
                    $text = 'Hoàn thành';
                } elseif ($status === 'CANCELLED') {
                    $badgeClass = 'badge-danger';
                    $text = 'Đã hủy';
                } elseif ($status === 'SHIPPING') {
                    $badgeClass = 'badge-warning';
                    $text = 'Đang giao';
                } else { // PENDING, CONFIRMED, ...
                    $badgeClass = 'badge-warning';
                    $text = 'Đang xử lý';
                }
                ?>
                <tr>
                    <td><?php echo $o['id']; ?></td>
                    <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($o['phone']); ?></td>
                    <td><?php echo number_format($o['total_cost']); ?>đ</td>
                    <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $text; ?></span></td>
                    <td><?php echo $o['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Hiện chưa có đơn hàng nào.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
