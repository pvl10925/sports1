<?php
$account = $_SESSION['account'] ?? null;
$orderId = $orderId ?? 0;
?>
<header>
  <div class="header-left">
    <div class="logo">SPORTS HUB</div>
    <nav class="nav-links">
      <a class="nav-link" href="index.php?c=home&a=index">Trang chủ</a>
      <a class="nav-link" href="index.php?c=product&a=index">Cửa hàng</a>
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

<div class="page-shell checkout-success-page">
  <section class="view-section">
    <div class="success-container">
      <div class="success-icon">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M20 6L9 17L4 12" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <h1 class="success-title">Đặt hàng thành công!</h1>
      <p class="success-message">
        Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được ghi nhận.
      </p>
      <?php if ($orderId > 0): ?>
        <div class="success-order-info">
          <p><strong>Mã đơn hàng:</strong> #<?php echo $orderId; ?></p>
          <p class="success-note">
            Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng.
          </p>
        </div>
      <?php endif; ?>
      <div class="success-actions">
        <a href="index.php?c=product&a=index" class="btn-primary">
          Tiếp tục mua sắm
        </a>
        <a href="index.php?c=home&a=index" class="btn-outline">
          Về trang chủ
        </a>
      </div>
    </div>
  </section>
</div>

<style>
.checkout-success-page {
  padding: 60px 20px;
  min-height: 60vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.success-container {
  max-width: 500px;
  text-align: center;
  background: #ffffff;
  border-radius: 16px;
  padding: 40px 32px;
  box-shadow: 0 4px 6px rgba(0,0,0,.1);
}

.success-icon {
  width: 80px;
  height: 80px;
  margin: 0 auto 24px;
  background: #d1fae5;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.success-title {
  font-size: 24px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 12px;
}

.success-message {
  font-size: 16px;
  color: #6b7280;
  margin-bottom: 24px;
  line-height: 1.6;
}

.success-order-info {
  background: #f9fafb;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 24px;
  text-align: left;
}

.success-order-info p {
  margin: 8px 0;
  font-size: 14px;
  color: #374151;
}

.success-order-info strong {
  color: #111827;
}

.success-note {
  font-size: 13px;
  color: #6b7280;
  margin-top: 12px;
  font-style: italic;
}

.success-actions {
  display: flex;
  gap: 12px;
  justify-content: center;
}

.success-actions .btn-primary,
.success-actions .btn-outline {
  padding: 12px 24px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 8px;
  text-decoration: none;
  display: inline-block;
  transition: all 0.2s;
}

.success-actions .btn-primary {
  background: #f97316;
  color: #111827;
  border: none;
}

.success-actions .btn-primary:hover {
  background: #ea580c;
}

.success-actions .btn-outline {
  background: transparent;
  color: #374151;
  border: 1px solid #d1d5db;
}

.success-actions .btn-outline:hover {
  background: #f9fafb;
  border-color: #9ca3af;
}

@media (max-width: 768px) {
  .success-container {
    padding: 32px 24px;
  }

  .success-actions {
    flex-direction: column;
  }

  .success-actions .btn-primary,
  .success-actions .btn-outline {
    width: 100%;
  }
}
</style>


