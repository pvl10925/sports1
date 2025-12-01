<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card mt-5 p-4 shadow-sm">
                <h3 class="card-title text-center">Quên Mật Khẩu</h3>
                <p class="text-center text-muted">Nhập email đã đăng ký của bạn để xem mật khẩu.</p>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <strong><?= $success ?></strong> 
                        <span class="text-primary font-weight-bold"><?= $decryptedPassword ?></span>
                        <p class="mt-2 mb-0">Hãy đăng nhập lại và đổi mật khẩu sớm nhất có thể!</p>
                    </div>
                    <div class="text-center">
                        <a href="index.php?c=auth&a=login" class="btn btn-primary btn-block w-100">Quay lại Đăng nhập</a>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Chỉ hiển thị form nếu chưa tìm thấy mật khẩu thành công
                if (empty($success)): 
                ?>
                <form action="index.php?c=auth&a=forgotPassword" method="POST">
                    <div class="form-group mb-3">
                        <label for="email">Địa chỉ Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= $email ?>" required>
                    </div>
                    
                    <button type="submit" class="btn btn-warning btn-block w-100">Tìm Mật Khẩu</button>
                    
                    <p class="text-center mt-3">
                        <a href="index.php?c=auth&a=login">Quay lại Đăng nhập</a>
                    </p>
                </form>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>