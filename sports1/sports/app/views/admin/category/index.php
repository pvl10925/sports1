<?php // $categories ?>
<h2>Danh mục sản phẩm</h2>
<p class="muted">Danh sách danh mục (CATEGORY) từ cơ sở dữ liệu.</p>

<div class="form-actions" style="justify-content:flex-start; margin-bottom:10px;">
    <button class="btn btn-primary"
            onclick="window.location='admin.php?c=category&a=create'">
        + Thêm danh mục
    </button>
</div>

<div style="overflow-x:auto;">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $c): ?>
                <tr>
                    <td><?php echo $c['id']; ?></td>
                    <td><?php echo htmlspecialchars($c['name']); ?></td>
                    <td><?php echo $c['created_at']; ?></td>
                    <td>
                        <button class="btn btn-outline"
                                onclick="window.location='admin.php?c=category&a=edit&id=<?php echo $c['id']; ?>'">
                            Sửa
                        </button>
                        <button class="btn btn-danger"
                                onclick="if(confirm('Xóa danh mục <?php echo htmlspecialchars($c['name']); ?>?')) window.location='admin.php?c=category&a=delete&id=<?php echo $c['id']; ?>';">
                            Xóa
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Chưa có danh mục nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
