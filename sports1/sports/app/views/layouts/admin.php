<?php
// app/views/layouts/admin.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Sports Shop</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <h1>MMT</h1>
        <nav class="nav">
            <a class="nav-item" href="admin.php?c=dashboard&a=index">Dashboard</a>
            <a class="nav-item" href="admin.php?c=product&a=index">Sản phẩm</a>
            <a class="nav-item" href="admin.php?c=category&a=index">Danh mục</a>
            <a class="nav-item" href="admin.php?c=brand&a=index">Nhãn hiệu</a>
            <a class="nav-item" href="admin.php?c=order&a=index">Đơn hàng</a>
            <a class="nav-item" href="admin.php?c=report&a=index">Báo cáo</a>
        </nav>
        <div class="sidebar-footer">
            Giao diện admin (MVC) · Sports Shop
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div class="topbar-title">
                <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Bảng điều khiển quản trị'; ?>
            </div>
            <div class="topbar-right">
                <?php if (!empty($_SESSION['account'])): ?>
                    Xin chào, <strong><?php echo htmlspecialchars($_SESSION['account']['username']); ?></strong>
                    (<?php echo htmlspecialchars($_SESSION['account']['role']); ?>)
                    · <a href="index.php?c=auth&a=logout" style="color:#fca5a5; text-decoration:none; margin-left:6px;">
                        Thoát admin
                      </a>
                <?php else: ?>
                    <a href="index.php?c=auth&a=login">Đăng nhập</a>
                <?php endif; ?>
            </div>

        </header>

        <section class="content">
            <?php echo $content; ?>
        </section>
    </main>
</div>
</body>
</html>
