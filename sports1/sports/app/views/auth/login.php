<div style="max-width: 420px; margin: 40px auto; background:#ffffff; border-radius:12px; box-shadow:0 10px 25px rgba(15,23,42,.12); padding:20px 22px; font-size:14px;">
    <h2 style="margin-top:0; margin-bottom:6px; font-size:20px;">Đăng nhập tài khoản</h2>
    <p style="margin-top:0; margin-bottom:14px; color:#6b7280;">
    </p>

    <?php if (!empty($error)): ?>
        <div style="background:#fee2e2; color:#991b1b; padding:8px 10px; border-radius:8px; font-size:13px; margin-bottom:10px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div style="margin-bottom:10px;">
            <label style="display:block; margin-bottom:4px; color:#4b5563;">Tên đăng nhập</label>
            <input 
                type="text" 
                name="username" 
                style="width:100%; padding:8px 10px; border-radius:999px; border:1px solid #d1d5db; outline:none;"
                placeholder="vd: admin"
                value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
            >
        </div>

        <div style="margin-bottom:10px;">
            <label style="display:block; margin-bottom:4px; color:#4b5563;">Mật khẩu</label>
            <input 
                type="password" 
                name="password" 
                style="width:100%; padding:8px 10px; border-radius:999px; border:1px solid #d1d5db; outline:none;"
                placeholder="••••••••"
            >
        </div>

        <button type="submit" 
                style="width:100%; margin-top:8px; padding:9px 0; border-radius:999px; border:none; background:#2563eb; color:#f9fafb; font-weight:600; cursor:pointer;">
            Đăng nhập
        </button>
        <p class="text-center mt-3 mb-1">
        <a href="index.php?c=auth&a=forgotPassword">Quên mật khẩu?</a>
    </p>

    <p class="text-center mt-1">
        Bạn chưa có tài khoản? 
        <a href="index.php?c=auth&a=register" class="btn btn-sm btn-success">Đăng ký ngay</a>
    </p>
</form>
    </form>
</div>
