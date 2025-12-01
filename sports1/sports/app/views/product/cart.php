<?php
$account = $_SESSION['account'] ?? null;
$cartItems = $cartItems ?? [];
$cartTotal = $cartTotal ?? 0;
$updated   = isset($_GET['updated']);
$removed   = isset($_GET['removed']);
$isEmpty   = empty($cartItems);
?>

<header>
  <div class="header-left">
    <div class="logo">SPORTS HUB</div>
    <nav class="nav-links">
      <a class="nav-link" href="index.php?c=home&a=index">Trang chủ</a>
      <a class="nav-link" href="index.php?c=product&a=index">Cửa hàng</a>
      <a class="nav-link" href="index.php?c=product&a=cart">Giỏ hàng</a>
    </nav>
  </div>

  <div class="header-right">
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

<div class="page-shell cart-page">
  <section class="view-section">
    <div class="section-header">
      <div>
        <div class="section-title">Giỏ hàng</div>
        <div class="section-sub">Kiểm tra sản phẩm trước khi thanh toán.</div>
      </div>
      <?php if (!$isEmpty): ?>
        <form method="post" action="index.php?c=product&a=clearCart">
          <button type="submit" class="btn-outline-light">Xóa toàn bộ</button>
        </form>
      <?php endif; ?>
    </div>

    <?php if ($updated): ?>
      <div class="detail-alert detail-alert-success">
        Đã cập nhật giỏ hàng.
      </div>
    <?php elseif ($removed): ?>
      <div class="detail-alert detail-alert-success">
        Đã xóa sản phẩm khỏi giỏ.
      </div>
    <?php endif; ?>

    <?php if ($isEmpty): ?>
      <div class="cart-empty">
        <p>Giỏ hàng của bạn đang trống.</p>
        <button class="btn-primary" onclick="window.location='index.php?c=product&a=index'">
          Tiếp tục mua sắm
        </button>
      </div>
    <?php else: ?>
      <div class="cart-layout">
        <form class="cart-table" method="post" action="index.php?c=product&a=updateCart">
          <table>
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tạm tính</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cartItems as $item): ?>
                <?php
                $itemId = (int)($item['id'] ?? 0);
                $quantity = (int)($item['quantity'] ?? 1);
                $price = (int)($item['price'] ?? 0);
                $subtotal = $price * $quantity;
                ?>
                <tr>
                  <td>
                    <div class="cart-product">
                      <?php if (!empty($item['image'])): ?>
                        <img src="<?php echo htmlspecialchars($item['image']); ?>"
                             alt="<?php echo htmlspecialchars($item['title']); ?>">
                      <?php endif; ?>
                      <div>
                        <div class="cart-title"><?php echo htmlspecialchars($item['title']); ?></div>
                        <div class="cart-meta">Mã #<?php echo $itemId; ?></div>
                      </div>
                    </div>
                  </td>
                  <td><?php echo number_format($price); ?>đ</td>
                  <td>
                    <input type="number"
                           name="quantity[<?php echo $itemId; ?>]"
                           min="1"
                           max="99"
                           value="<?php echo $quantity; ?>">
                  </td>
                  <td><?php echo number_format($subtotal); ?>đ</td>
                  <td>
                    <button type="submit"
                            class="link-btn"
                            name="product_id"
                            value="<?php echo $itemId; ?>"
                            formaction="index.php?c=product&a=removeFromCart"
                            formmethod="post">
                      Xóa
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="cart-table-actions">
            <button type="submit" class="btn-outline">Cập nhật giỏ hàng</button>
          </div>
        </form>

        <div class="cart-summary">
          <h3>Tóm tắt đơn hàng</h3>
          <div class="cart-summary-row">
            <span>Tổng tiền</span>
            <strong><?php echo number_format($cartTotal); ?>đ</strong>
          </div>
          <div class="cart-summary-row cart-shipping">
            <span>Phí vận chuyển</span>
            <span>Miễn phí cho đơn > 999.000đ</span>
          </div>
          <a href="index.php?c=product&a=checkout" class="btn-primary cart-checkout" style="display: block; text-align: center; text-decoration: none;">
            Thanh toán
          </a>
          <button class="btn-outline cart-continue"
                  onclick="window.location='index.php?c=product&a=index'">
            Tiếp tục mua sắm
          </button>
        </div>
      </div>
    <?php endif; ?>
  </section>
</div>

