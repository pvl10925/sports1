<?php
$account = $_SESSION['account'] ?? null;
$cartItems = $cartItems ?? [];
$cartTotal = $cartTotal ?? 0;
$userData = $userData ?? null;
$error = isset($_GET['error']);
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

<div class="page-shell checkout-page">
  <section class="view-section">
    <div class="section-header">
      <div>
        <div class="section-title">Thanh toán</div>
        <div class="section-sub">Vui lòng điền đầy đủ thông tin để hoàn tất đơn hàng.</div>
      </div>
    </div>

    <?php if ($error): ?>
      <div class="detail-alert detail-alert-danger">
        Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại.
      </div>
    <?php endif; ?>

    <div class="checkout-layout">
      <div class="checkout-form-wrapper">
        <form class="checkout-form" method="post" action="index.php?c=product&a=processCheckout">
          <h3>Thông tin khách hàng</h3>
          
          <div class="form-group">
            <label for="customer_name">Họ và tên <span class="required">*</span></label>
            <input type="text" 
                   id="customer_name" 
                   name="customer_name" 
                   required
                   value="<?php echo htmlspecialchars($userData['name'] ?? ''); ?>"
                   placeholder="Nhập họ và tên">
          </div>

          <div class="form-group">
            <label for="phone">Số điện thoại <span class="required">*</span></label>
            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   required
                   value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>"
                   placeholder="Nhập số điện thoại">
          </div>

          <div class="form-group">
            <label for="address">Địa chỉ giao hàng <span class="required">*</span></label>
            <textarea id="address" 
                      name="address" 
                      required
                      rows="3"
                      placeholder="Nhập địa chỉ chi tiết"><?php echo htmlspecialchars($userData['address'] ?? ''); ?></textarea>
          </div>

          <div class="checkout-actions">
            <button type="button" 
                    class="btn-outline"
                    onclick="window.location='index.php?c=product&a=cart'">
              Quay lại giỏ hàng
            </button>
            <button type="submit" class="btn-primary">
              Xác nhận đặt hàng
            </button>
          </div>
        </form>
      </div>

      <div class="checkout-summary">
        <h3>Đơn hàng của bạn</h3>
        <div class="checkout-items">
          <?php foreach ($cartItems as $item): ?>
            <?php
            $itemId = (int)($item['id'] ?? 0);
            $quantity = (int)($item['quantity'] ?? 1);
            $price = (int)($item['price'] ?? 0);
            $subtotal = $price * $quantity;
            ?>
            <div class="checkout-item">
              <div class="checkout-item-image">
                <?php if (!empty($item['image'])): ?>
                  <img src="<?php echo htmlspecialchars($item['image']); ?>"
                       alt="<?php echo htmlspecialchars($item['title']); ?>">
                <?php endif; ?>
              </div>
              <div class="checkout-item-info">
                <div class="checkout-item-title"><?php echo htmlspecialchars($item['title']); ?></div>
                <div class="checkout-item-meta">
                  <span>Số lượng: <?php echo $quantity; ?></span>
                  <span class="checkout-item-price"><?php echo number_format($subtotal); ?>đ</span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="checkout-total">
          <div class="checkout-total-row">
            <span>Tổng tiền:</span>
            <strong><?php echo number_format($cartTotal); ?>đ</strong>
          </div>
          <div class="checkout-total-note">
            <small>Phí vận chuyển sẽ được tính sau khi xác nhận đơn hàng.</small>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<style>
.checkout-page {
  padding-bottom: 40px;
}

.checkout-layout {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 24px;
  margin-top: 20px;
}

.checkout-form-wrapper {
  background: #ffffff;
  border-radius: 14px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0,0,0,.08);
}

.checkout-form h3 {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 20px;
  color: #111827;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 8px;
  color: #374151;
}

.form-group .required {
  color: #ef4444;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  font-family: inherit;
  transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #3b82f6;
}

.form-group textarea {
  resize: vertical;
  min-height: 80px;
}

.checkout-actions {
  display: flex;
  gap: 12px;
  margin-top: 24px;
  padding-top: 20px;
  border-top: 1px solid #e5e7eb;
}

.checkout-actions .btn-outline,
.checkout-actions .btn-primary {
  flex: 1;
  padding: 12px 20px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
}

.checkout-actions .btn-primary {
  background: #f97316;
  color: #111827;
  border: none;
}

.checkout-actions .btn-primary:hover {
  background: #ea580c;
}

.checkout-summary {
  background: #ffffff;
  border-radius: 14px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0,0,0,.08);
  height: fit-content;
  position: sticky;
  top: 80px;
}

.checkout-summary h3 {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 20px;
  color: #111827;
}

.checkout-items {
  margin-bottom: 20px;
}

.checkout-item {
  display: flex;
  gap: 12px;
  padding: 12px 0;
  border-bottom: 1px solid #e5e7eb;
}

.checkout-item:last-child {
  border-bottom: none;
}

.checkout-item-image {
  width: 60px;
  height: 60px;
  border-radius: 8px;
  overflow: hidden;
  background: #f3f4f6;
  flex-shrink: 0;
}

.checkout-item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.checkout-item-info {
  flex: 1;
  min-width: 0;
}

.checkout-item-title {
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 4px;
  color: #111827;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.checkout-item-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 12px;
  color: #6b7280;
}

.checkout-item-price {
  font-weight: 600;
  color: #111827;
}

.checkout-total {
  padding-top: 20px;
  border-top: 2px solid #e5e7eb;
}

.checkout-total-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 16px;
  margin-bottom: 12px;
}

.checkout-total-row strong {
  font-size: 20px;
  color: #f97316;
}

.checkout-total-note {
  font-size: 12px;
  color: #6b7280;
  line-height: 1.5;
}

.detail-alert {
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 14px;
}

.detail-alert-danger {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

@media (max-width: 768px) {
  .checkout-layout {
    grid-template-columns: 1fr;
  }

  .checkout-summary {
    position: static;
  }
}
</style>


