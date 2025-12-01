<?php
$productTitle = htmlspecialchars($product['title'] ?? 'Sản phẩm');
$brandName    = htmlspecialchars($product['brand_name'] ?? 'Đang cập nhật');
$categoryName = htmlspecialchars($product['category_name'] ?? 'Khác');
$priceValue   = isset($product['price']) ? number_format($product['price']) . 'đ' : 'Liên hệ';
$stock        = isset($product['number_in_stock']) ? (int)$product['number_in_stock'] : null;
$sold         = isset($product['number_sold']) ? (int)$product['number_sold'] : null;
$imageUrl     = !empty($product['image']) ? htmlspecialchars($product['image']) : null;
$addedToCart  = isset($_GET['added']);

$account   = $_SESSION['account'] ?? null;
$cartItems = $_SESSION['cart'] ?? [];
$cartCount = 0;
foreach ($cartItems as $item) {
    $cartCount += (int)($item['quantity'] ?? 0);
}
?>

<header>
  <div class="header-left">
    <div class="logo">SPORTS HUB</div>
    <nav class="nav-links">
      <a class="nav-link" href="index.php?c=home&a=index">Trang chủ</a>
      <a class="nav-link active" href="index.php?c=product&a=index">Cửa hàng</a>
      <a class="nav-link" href="index.php?c=auth&a=login">Tài khoản</a>
    </nav>
  </div>

  <div class="header-right">
    <div class="search-box">
      <form method="get" action="index.php">
        <input type="hidden" name="c" value="product">
        <input type="hidden" name="a" value="index">
        <input name="q" placeholder="Tìm vợt, giày, phụ kiện..." />
      </form>
    </div>
    <a class="icon-btn cart-button" href="index.php?c=product&a=cart">
      <span>Giỏ hàng</span>
      <span class="badge-pill"><?php echo $cartCount; ?></span>
    </a>
    <?php if ($account): ?>
      <div class="account-pill">
        <div class="account-avatar">
          <?php echo strtoupper(substr($account['username'] ?? 'U', 0, 1)); ?>
        </div>
        <div class="account-info">
          <div class="account-label">Xin chào</div>
          <div class="account-name"><?php echo htmlspecialchars($account['username']); ?></div>
        </div>
        <a class="account-action" href="index.php?c=auth&a=logout">Đăng xuất</a>
      </div>
    <?php else: ?>
      <button class="btn-outline-light"
              onclick="window.location='index.php?c=auth&a=login'">
        Đăng nhập
      </button>
    <?php endif; ?>
  </div>
</header>

<div class="page-shell">
  <section class="view-section product-detail-shell">
    <div class="detail-breadcrumb">
      <a href="index.php?c=home&a=index">Trang chủ</a>
      <span>/</span>
      <a href="index.php?c=product&a=index">Cửa hàng</a>
      <span>/</span>
      <span><?php echo $productTitle; ?></span>
    </div>

    <div class="product-detail-grid">
      <div class="detail-media">
        <?php if ($imageUrl): ?>
          <img class="detail-img" src="<?php echo $imageUrl; ?>" alt="<?php echo $productTitle; ?>">
        <?php else: ?>
          <div class="detail-img detail-img-placeholder">
            <span>Hình ảnh đang cập nhật</span>
          </div>
        <?php endif; ?>
      </div>

      <div class="detail-info">
        <div class="detail-label">Mã sản phẩm #<?php echo (int)($product['id'] ?? 0); ?></div>
        <h1 class="detail-title"><?php echo $productTitle; ?></h1>

        <div class="detail-meta">
          <span>Thương hiệu: <strong><?php echo $brandName; ?></strong></span>
          <span>Danh mục: <strong><?php echo $categoryName; ?></strong></span>
        </div>

        <div class="detail-price-row">
          <div class="detail-price"><?php echo $priceValue; ?></div>
          <?php if ($stock !== null): ?>
            <div class="detail-stock <?php echo $stock > 0 ? 'in-stock' : 'out-stock'; ?>">
              <?php echo $stock > 0 ? "Còn $stock sản phẩm" : 'Hết hàng'; ?>
            </div>
          <?php endif; ?>
        </div>

        <?php if ($sold !== null): ?>
          <div class="detail-sold">Đã bán: <?php echo number_format($sold); ?></div>
        <?php endif; ?>

        <?php if ($addedToCart): ?>
          <div class="detail-alert detail-alert-success">
            Đã thêm sản phẩm vào giỏ hàng. <a href="index.php?c=product&a=index#cart">Xem giỏ</a>
          </div>
        <?php endif; ?>

        <div class="detail-description">
          <h3>Mô tả sản phẩm</h3>
          <p><?php echo nl2br(htmlspecialchars($product['description'] ?? 'Thông tin đang được cập nhật.')); ?></p>
        </div>

        <div class="detail-actions">
          <form class="add-cart-form" method="post" action="index.php?c=product&a=addToCart">
            <input type="hidden" name="product_id" value="<?php echo (int)($product['id'] ?? 0); ?>">
            <label class="detail-qty-label" for="quantityInput">Số lượng</label>
            <div class="detail-qty-control">
              <button class="qty-btn" type="button" data-step="-1">-</button>
              <input id="quantityInput"
                     type="number"
                     name="quantity"
                     min="1"
                     max="<?php echo ($stock !== null && $stock > 0) ? $stock : 99; ?>"
                     value="1">
              <button class="qty-btn" type="button" data-step="1">+</button>
            </div>
            <button class="btn-primary detail-btn" type="submit" <?php echo ($stock === 0) ? 'disabled' : ''; ?>>
              <?php echo ($stock === 0) ? 'Hết hàng' : 'Thêm vào giỏ'; ?>
            </button>
          </form>
          <button class="btn-outline detail-btn" onclick="window.location='index.php?c=product&a=index'">
            Tiếp tục mua sắm
          </button>
        </div>

        <div class="detail-stats">
          <div class="detail-stat">
            <span class="detail-stat-label">Bảo hành</span>
            <span class="detail-stat-value">12 tháng</span>
          </div>
          <div class="detail-stat">
            <span class="detail-stat-label">Đổi trả</span>
            <span class="detail-stat-value">7 ngày miễn phí</span>
          </div>
          <div class="detail-stat">
            <span class="detail-stat-label">Giao hàng</span>
            <span class="detail-stat-value">Toàn quốc</span>
          </div>
        </div>
      </div>
    </div>

    <div class="detail-more">
      <h3>Có thể bạn quan tâm</h3>
      <p>Khám phá thêm nhiều sản phẩm thể thao chính hãng tại cửa hàng Sports Hub.</p>
      <a class="detail-link" href="index.php?c=product&a=index">&laquo; Quay lại cửa hàng</a>
    </div>
  </section>
</div>

<script>
  (function () {
    const qtyControl = document.querySelector('.detail-qty-control');
    if (!qtyControl) return;
    const input = qtyControl.querySelector('input[name="quantity"]');
    qtyControl.addEventListener('click', function (e) {
      const btn = e.target.closest('.qty-btn');
      if (!btn) return;
      e.preventDefault();
      const step = Number(btn.dataset.step || 0);
      let value = Number(input.value || 1) + step;
      const min = Number(input.min || 1);
      const max = Number(input.max || 99);
      value = Math.min(Math.max(value, min), max);
      input.value = value;
    });
  })();
</script>
