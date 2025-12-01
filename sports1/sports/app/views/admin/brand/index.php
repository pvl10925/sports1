<?php // $brands ?>
<h2>Nhãn hiệu</h2>
<p class="muted">Danh sách nhãn hiệu (BRAND) từ cơ sở dữ liệu.</p>

<div class="form-actions" style="justify-content:flex-start; margin-bottom:10px;">
    <button class="btn btn-primary"
            onclick="window.location='admin.php?c=brand&a=create'">
        + Thêm nhãn hiệu
    </button>
</div>

<div style="overflow-x:auto;">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên nhãn hiệu</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($brands)): ?>
            <?php foreach ($brands as $b): ?>
                <tr>
                    <td><?php echo $b['id']; ?></td>
                    <td><?php echo htmlspecialchars($b['name']); ?></td>
                    <td><?php echo htmlspecialchars($b['description'] ?? ''); ?></td>
                    <td>
                        <button class="btn btn-outline"
                                onclick="window.location='admin.php?c=brand&a=edit&id=<?php echo $b['id']; ?>'">
                            Sửa
                        </button>
                        <button class="btn btn-danger"
                                onclick="if(confirm('Xóa nhãn hiệu <?php echo htmlspecialchars($b['name']); ?>?')) window.location='admin.php?c=brand&a=delete&id=<?php echo $b['id']; ?>';">
                            Xóa
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Chưa có nhãn hiệu nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
