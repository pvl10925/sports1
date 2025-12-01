<?php
// $mode: 'create' hoặc 'edit'
// $category: null (create) hoặc mảng (edit)
// $error: thông báo lỗi nếu có

$isEdit = ($mode === 'edit');
$actionUrl = $isEdit
    ? 'admin.php?c=category&a=edit&id=' . $category['id']
    : 'admin.php?c=category&a=create';

$currentName = $category['name'] ?? '';
?>
<h2><?php echo $isEdit ? 'Sửa danh mục' : 'Thêm danh mục'; ?></h2>
<p class="muted">
    <?php echo $isEdit
        ? 'Chỉnh sửa tên danh mục trong bảng CATEGORY.'
        : 'Thêm mới danh mục vào bảng CATEGORY.'; ?>
</p>

<?php if (!empty($error)): ?>
    <div style="background:#fee2e2; color:#991b1b; padding:8px 10px; border-radius:8px; font-size:13px; margin-bottom:10px;">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<form method="post" action="<?php echo $actionUrl; ?>">
    <div class="form-grid">
        <div class="form-group">
            <label>Tên danh mục</label>
            <input name="name" required
                   value="<?php echo htmlspecialchars($currentName); ?>"
                   placeholder="VD: Vợt, Giày, Phụ kiện...">
        </div>
    </div>
    <div class="form-actions">
        <button type="button" class="btn btn-outline"
                onclick="window.location='admin.php?c=category&a=index'">
            Hủy
        </button>
        <button type="submit" class="btn btn-primary">
            <?php echo $isEdit ? 'Lưu thay đổi' : 'Lưu danh mục'; ?>
        </button>
    </div>
</form>
