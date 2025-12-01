<?php
// $mode: 'create' hoặc 'edit'
// $product: null (create) hoặc mảng dữ liệu (edit / lỗi validate)
// $brands, $categories, $error

$isEdit = ($mode === 'edit');

// Giá trị mặc định
$title       = $product['title']           ?? '';
$brandId     = $product['brand_id']        ?? '';
$categoryId  = $product['category_id']     ?? '';
$price       = $product['price']           ?? '';
$stock       = $product['number_in_stock'] ?? 0;
$datePub     = $product['date_publication']?? '';
$image       = $product['image']           ?? '';
$description = $product['description']     ?? '';
?>
<h2><?php echo $isEdit ? 'Sửa sản phẩm' : 'Thêm sản phẩm'; ?></h2>
<p class="muted">
    <?php echo $isEdit
        ? 'Chỉnh sửa thông tin sản phẩm trong bảng PRODUCT.'
        : 'Thêm mới sản phẩm vào bảng PRODUCT.'; ?>
</p>

<?php if (!empty($error)): ?>
    <div style="background:#fee2e2; color:#991b1b; padding:8px 10px; border-radius:8px; font-size:13px; margin-bottom:10px;">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <div class="form-grid">
        <div class="form-group">
            <label>Tên sản phẩm</label>
            <input name="title" required
                   value="<?php echo htmlspecialchars($title); ?>"
                   placeholder="VD: Vợt cầu lông Yonex Astrox 88D">
        </div>

        <div class="form-group">
            <label>Thương hiệu</label>
            <select name="brand_id" required>
                <option value="">-- Chọn thương hiệu --</option>
                <?php foreach ($brands as $b): ?>
                    <option value="<?php echo $b['id']; ?>"
                        <?php echo ($b['id'] == $brandId) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($b['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Danh mục</label>
            <select name="category_id" required>
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?php echo $c['id']; ?>"
                        <?php echo ($c['id'] == $categoryId) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Giá (VNĐ)</label>
            <input type="number" name="price" min="0" step="1000" required
                   value="<?php echo htmlspecialchars((string)$price); ?>">
        </div>

        <div class="form-group">
            <label>Số lượng tồn kho</label>
            <input type="number" name="stock" min="0" step="1"
                   value="<?php echo (int)$stock; ?>">
        </div>

        <div class="form-group">
            <label>Ngày phát hành</label>
            <input type="date" name="date_publication"
                   value="<?php echo htmlspecialchars($datePub); ?>">
        </div>

        <div class="form-group">
            <label>Ảnh sản phẩm</label>

            <?php if ($isEdit && !empty($image)): ?>
                <div style="margin-bottom:8px;">
                    <img src="<?php echo htmlspecialchars($image); ?>"
                         alt="Ảnh hiện tại"
                         style="max-width:160px;border-radius:8px;">
                </div>
            <?php endif; ?>

            <input type="file" name="image" id="image" accept="image/*"
                <?php echo $isEdit ? '' : 'required'; ?>>

            <p class="form-hint" style="font-size:12px;color:#6b7280;margin-top:4px;">
                <?php if ($isEdit): ?>
                    Bỏ trống nếu muốn giữ ảnh hiện tại. Nếu chọn ảnh mới, hệ thống sẽ upload
                    vào <code>uploads/products</code> và cập nhật đường dẫn.
                <?php else: ?>
                    Chọn file ảnh (.jpg, .png, .webp). Hệ thống sẽ upload vào
                    <code>uploads/products</code>.
                <?php endif; ?>
            </p>
        </div>
    </div>

    <div class="form-group" style="margin-top:10px;">
        <label>Mô tả</label>
        <textarea name="description" placeholder="Mô tả ngắn về sản phẩm..."><?php
            echo htmlspecialchars($description);
        ?></textarea>
    </div>

    <div class="form-actions">
        <button type="button" class="btn btn-outline"
                onclick="window.location='admin.php?c=product&a=index'">
            Hủy
        </button>
        <button type="submit" class="btn btn-primary">
            <?php echo $isEdit ? 'Lưu thay đổi' : 'Lưu sản phẩm'; ?>
        </button>
    </div>
</form>
