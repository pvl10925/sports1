<h2>Quản lý sản phẩm</h2>
<p class="muted">
    Danh sách sản phẩm lấy <strong>trực tiếp từ cơ sở dữ liệu</strong> (PRODUCT + BRAND + CATEGORY).
</p>

<div class="toolbar">
    <input type="search" placeholder="(Demo) Tìm theo tên sản phẩm..." disabled>
    <div style="display:flex; gap:8px; align-items:center;">
        <select disabled>
            <option>Danh mục (demo)</option>
        </select>
        <button class="btn btn-primary" onclick="window.location='admin.php?c=product&a=create'">
            + Thêm sản phẩm
        </button>
    </div>
</div>

<div style="overflow-x:auto;">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Thương hiệu</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Đã bán</th>
            <th>Tồn kho</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <?php
                $stock = (int)$p['number_in_stock'];
                if ($stock <= 0) {
                    $statusClass = 'badge-danger';
                    $statusText  = 'Hết hàng';
                } elseif ($stock <= 5) {
                    $statusClass = 'badge-warning';
                    $statusText  = 'Sắp hết';
                } else {
                    $statusClass = 'badge-success';
                    $statusText  = 'Đang bán';
                }
                ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo htmlspecialchars($p['title']); ?></td>
                    <td><?php echo htmlspecialchars($p['brand_name']); ?></td>
                    <td><?php echo htmlspecialchars($p['category_name']); ?></td>
                    <td><?php echo number_format($p['price']); ?>đ</td>
                    <td><?php echo (int)$p['number_sold']; ?></td>
                    <td><?php echo $stock; ?></td>
                    <td>
                        <span class="badge <?php echo $statusClass; ?>">
                            <?php echo $statusText; ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-outline"
                                onclick="window.location='admin.php?c=product&a=edit&id=<?php echo $p['id']; ?>'">
                            Sửa
                        </button>

                        <button class="btn btn-danger"
                                onclick="if(confirm('Xóa sản phẩm <?php echo htmlspecialchars($p['title']); ?>?')) window.location='admin.php?c=product&a=delete&id=<?php echo $p['id']; ?>';">
                            Xóa
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="9">Chưa có sản phẩm nào trong hệ thống.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
