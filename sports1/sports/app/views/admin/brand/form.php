<?php // $error ?>
<h2>Thêm nhãn hiệu</h2>
<p class="muted">Thêm mới nhãn hiệu vào bảng BRAND.</p>

<?php if (!empty($error)): ?>
    <div style="background:#fee2e2; color:#991b1b; padding:8px 10px; border-radius:8px; font-size:13px; margin-bottom:10px;">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<form method="post">
    <div class="form-grid">
        <div class="form-group">
            <label>Tên nhãn hiệu</label>
            <input name="name" required placeholder="VD: Yonex, Nike, Adidas...">
        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <input name="description" placeholder="VD: Thương hiệu Nhật chuyên vợt cầu lông">
        </div>
    </div>
    <div class="form-actions">
        <button type="button" class="btn btn-outline"
                onclick="window.location='admin.php?c=brand&a=index'">
            Hủy
        </button>
        <button type="submit" class="btn btn-primary">
            Lưu nhãn hiệu
        </button>
    </div>
</form>
