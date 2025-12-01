<?php
// $orders: danh sách đơn hàng từ DB
?>
<h2>Quản lý đơn hàng</h2>
<p class="muted">
    Danh sách đơn hàng (ORDERS) lấy từ cơ sở dữ liệu. Bạn có thể hủy các đơn đang xử lý ngay tại đây.
</p>

<div style="overflow-x:auto;">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Khách hàng</th>
            <th>Địa chỉ</th>
            <th>SĐT</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Thời gian tạo</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $o): ?>
                <?php
                $status = $o['status'];
                if ($status === 'COMPLETED') {
                    $badgeClass = 'badge-success'; 
                    $text       = 'Hoàn thành';
                } elseif ($status === 'CANCELLED') {
                    $badgeClass = 'badge-danger';  
                    $text       = 'Đã hủy';
                } elseif ($status === 'SHIPPING') {
                    $badgeClass = 'badge-warning'; 
                    $text       = 'Đang giao';
                } else { // PENDING, CONFIRMED, ...
                    $badgeClass = 'badge-warning'; 
                    $text       = 'Đang xử lý';
                }
                ?>
                <tr>
                    <td><?php echo $o['id']; ?></td>
                    <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($o['address']); ?></td>
                    <td><?php echo htmlspecialchars($o['phone']); ?></td>
                    <td><?php echo number_format($o['total_cost']); ?>đ</td>
                    <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $text; ?></span></td>
                    <td><?php echo $o['created_at']; ?></td>
                    <td>
                        <?php if ($status !== 'CANCELLED' && $status !== 'COMPLETED'): ?>
                            <button class="btn btn-danger"
                                    onclick="if(confirm('Bạn có chắc muốn hủy đơn #<?php echo $o['id']; ?>?')) window.location='admin.php?c=order&a=cancel&id=<?php echo $o['id']; ?>';">
                                Hủy đơn
                            </button>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8">Chưa có đơn hàng nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
