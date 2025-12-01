<?php
// $products: danh sách sản phẩm từ ProductController
// bạn có thể thêm $productCount = count($products) trong controller nếu thích
$productCount = isset($products) ? count($products) : 0;
$account = $_SESSION['account'] ?? null;
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
        <input name="q" placeholder="Tìm vợt, giày, phụ kiện..."
               value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" />
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

<section class="shop-hero">
  <div class="shop-hero-content">
    <p class="shop-badge">Cửa hàng chính hãng</p>
    <h1>Thiết bị thể thao cho mọi môn bạn yêu thích.</h1>
    <p class="shop-sub">
      Bộ sưu tập mới nhất từ các thương hiệu hàng đầu. Giao nhanh trong 48h, hỗ trợ đổi trả dễ dàng.
    </p>
    <div class="shop-cta">
      <button class="btn-primary" onclick="document.getElementById('filterForm').scrollIntoView({behavior: 'smooth'});">
        Khám phá ngay
      </button>
      <button class="btn-outline"
              onclick="window.location='index.php?c=home&a=index'">
        Về trang chủ
      </button>
    </div>
    <div class="shop-stats">
      <div>
        <strong>1200+</strong>
        <span>Khách hàng hài lòng</span>
      </div>
      <div>
        <strong>72h</strong>
        <span>Hoàn hàng dễ dàng</span>
      </div>
      <div>
        <strong>100%</strong>
        <span>Sản phẩm chính hãng</span>
      </div>
    </div>
  </div>
  <div class="shop-hero-visual">
    <div class="shop-card">
      <div class="shop-card-title">Flash Sales</div>
      <p>Giảm đến 30% cho giày chạy tháng này.</p>
      <button class="btn-primary small"
              onclick="window.location='index.php?c=product&a=index&category=1';">
        Xem ưu đãi
      </button>
    </div>
    <div class="shop-hero-circle"></div>
  </div>
</section>

<div class="page-shell">
  <section class="view-section">
    <div class="section-header">
      <div>
        <div class="section-title">Cửa hàng</div>
        <div class="section-sub">
          Lọc theo danh mục, nhãn hiệu, khoảng giá...
        </div>
      </div>
    </div>

    <div class="products-layout">
      <!-- SIDEBAR -->
      <aside class="sidebar">
        <form id="filterForm" method="get" action="index.php">
          <input type="hidden" name="c" value="product">
          <input type="hidden" name="a" value="index">

        <div class="sidebar-group">
          <h3>Danh mục</h3>
            <label>
              <input type="radio" name="category" value="0"
                     <?php echo ($categoryId == 0) ? 'checked' : ''; ?>>
              Tất cả
            </label>
            <?php if (!empty($categories)): ?>
              <?php foreach ($categories as $cat): ?>
                <label>
                  <input type="radio"
                         name="category"
                         value="<?php echo $cat['id']; ?>"
                      <?php echo ($categoryId == $cat['id']) ? 'checked' : ''; ?>>
                  <?php echo htmlspecialchars($cat['name']); ?>
                </label>
              <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="sidebar-group">
          <h3>Nhãn hiệu</h3>
            <label>
              <input type="radio" name="brand" value="0"
                     <?php echo ($brandId == 0) ? 'checked' : ''; ?>>
              Tất cả
            </label>
            <?php if (!empty($brands)): ?>
              <?php foreach ($brands as $b): ?>
                <label>
                  <input type="radio"
                         name="brand"
                         value="<?php echo $b['id']; ?>"
                      <?php echo ($brandId == $b['id']) ? 'checked' : ''; ?>>
                  <?php echo htmlspecialchars($b['name']); ?>
                </label>
              <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="sidebar-group">
          <h3>Khoảng giá</h3>
            <label>
              <input type="radio" name="price" value="all"
                     <?php echo ($priceRange === 'all') ? 'checked' : ''; ?>>
              Tất cả
            </label>
            <label>
              <input type="radio" name="price" value="1"
                     <?php echo ($priceRange === '1') ? 'checked' : ''; ?>>
              Dưới 500k
            </label>
            <label>
              <input type="radio" name="price" value="2"
                     <?php echo ($priceRange === '2') ? 'checked' : ''; ?>>
              500k - 1.5tr
            </label>
            <label>
              <input type="radio" name="price" value="3"
                     <?php echo ($priceRange === '3') ? 'checked' : ''; ?>>
              Trên 1.5tr
            </label>
        </div>
        </form>
      </aside>

      <!-- LIST PRODUCT -->
      <div>
        <div class="product-toolbar">
          <div class="product-count">
            <?php echo $productCount; ?> sản phẩm
          </div>
          <div>
            <select id="sortSelect">
              <option value="default">Sắp xếp</option>
              <option value="price-asc">Giá tăng dần</option>
              <option value="price-desc">Giá giảm dần</option>
              <option value="sold-desc">Bán chạy</option>
            </select>
          </div>
        </div>

        <div class="product-grid">
          <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
              <a class="product-card"
                 href="index.php?c=product&a=detail&id=<?php echo $p['id']; ?>">
                <?php if (!empty($p['image'])): ?>
                  <div class="product-image-wrap">
                    <img src="<?php echo htmlspecialchars($p['image']); ?>"
                         alt="<?php echo htmlspecialchars($p['title']); ?>">
                  </div>
                <?php endif; ?>
                <div class="product-body">
                  <div class="product-title">
                    <?php echo htmlspecialchars($p['title']); ?>
                  </div>
                  <div class="product-meta">
                    <?php echo htmlspecialchars($p['brand_name'] ?? ''); ?>
                    <?php if (!empty($p['category_name'])): ?>
                      · <?php echo htmlspecialchars($p['category_name']); ?>
                    <?php endif; ?>
                  </div>
                  <div class="product-price-row">
                    <div class="product-price">
                      <?php echo number_format($p['price']); ?>đ
                    </div>
                  </div>
                  <?php if (isset($p['number_in_stock'])): ?>
                    <div class="product-stock">
                      Còn <?php echo (int)$p['number_in_stock']; ?> sản phẩm
                    </div>
                  <?php endif; ?>
                </div>
              </a>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Chưa có sản phẩm nào.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  (function () {
    const filterForm = document.getElementById('filterForm');
    if (!filterForm) return;

    filterForm.querySelectorAll('input[type="radio"]').forEach(input => {
      input.addEventListener('change', () => filterForm.submit());
    });
  })();
</script>

<footer>
  Cửa hàng Sports Hub – dữ liệu từ MySQL.
</footer>
