<?php
// Lấy giá trị cũ từ $_POST để giữ lại sau khi submit lỗi
$oldUsername = htmlspecialchars($_POST['username'] ?? '');
$oldEmail    = htmlspecialchars($_POST['email'] ?? '');
$oldName     = htmlspecialchars($_POST['name'] ?? '');
$oldPhone    = htmlspecialchars($_POST['phone'] ?? '');
$oldAddress  = htmlspecialchars($_POST['address'] ?? '');
?>

<div class="auth-wrapper">
    <div class="auth-container">
        <h2>Đăng ký tài khoản mới</h2>
        
        <?php 
        // Biến $error này phải được truyền từ AuthController nếu có lỗi
        if (isset($error) && !empty($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <form action="index.php?c=auth&a=handleRegister" method="POST" class="auth-form">
            
            <div class="form-group">
                <label for="username">Tên đăng nhập *</label>
                <input type="text" id="username" name="username" required value="<?= $oldUsername ?>">
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu *</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="<?= $oldEmail ?>">
            </div>
            
            <div class="form-group">
                <label for="name">Họ và Tên *</label>
                <input type="text" id="name" name="name" required value="<?= $oldName ?>">
            </div>
            
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" id="phone" name="phone" value="<?= $oldPhone ?>">
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" id="address" name="address" value="<?= $oldAddress ?>">
            </div>

            <button type="submit" class="btn btn-primary">Đăng ký</button>
            <p class="auth-link">Đã có tài khoản? <a href="index.php?c=auth&a=login">Đăng nhập ngay</a></p>
        </form>
    </div>
</div>