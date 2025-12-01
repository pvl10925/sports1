<?php
if (!function_exists('slugify_vi')) {
    function slugify_vi($text)
    {
        $text = $text ?? '';
        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        if ($ascii !== false) {
            $text = $ascii;
        }
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        return $text ?: 'khac';
    }
}

$account = $_SESSION['account'] ?? null;
$cartItems = $_SESSION['cart'] ?? [];
$cartCount = 0;
foreach ($cartItems as $item) {
    $cartCount += (int)($item['quantity'] ?? 0);
}
?>
<header>
    <div class="header-left">
        <div class="logo">
            <img src="https://tse4.mm.bing.net/th/id/OIP.UuF0M9iZvoNaoMCpIfR0UAHaHa?pid=Api&H=160&W=160" alt="Sports Hub Logo" class="logo-img">
            <span class="logo-text">SPORTS HUB</span>
        </div>
        <nav class="nav-links">
            <a class="nav-link active" href="index.php?c=home&a=index">Trang chủ</a>
            <a class="nav-link" href="index.php?c=auth&a=login">Tài khoản</a>
        </nav>
    </div>

    <div class="header-right">
        <div class="search-box">
            <form method="get" action="index.php">
                <input type="hidden" name="c" value="home">
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
                    onclick="window.location='index.php?c=auth&a=register'">
                Đăng ký
            </button>
            <button class="btn-outline-light"
                    onclick="window.location='index.php?c=auth&a=login'">
                Đăng nhập
            </button>
        <?php endif; ?>
    </div>
</header>

<div class="menu-bar">
    <div class="menu-bar-container">
        <a href="index.php?c=home&a=index" 
           class="menu-item <?php echo (empty($categoryId) && empty($brandId) && empty($_GET['q']) && empty($_GET['c'])) ? 'active' : ''; ?>">
            TRANG CHỦ
        </a>
        
        <?php if (!empty($categories)): ?>
        <div class="menu-item dropdown">
            <a href="javascript:void(0)" class="menu-link">
                DANH MỤC
                <span class="dropdown-arrow">▼</span>
            </a>
            <div class="dropdown-menu">
                <a href="index.php?c=home&a=index" class="dropdown-item">
                    Tất cả danh mục
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="index.php?c=home&a=index&category=<?php echo $cat['id']; ?>" 
                       class="dropdown-item <?php echo (isset($categoryId) && $categoryId == $cat['id']) ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($brands)): ?>
        <div class="menu-item dropdown">
            <a href="javascript:void(0)" class="menu-link">
                THƯƠNG HIỆU
                <span class="dropdown-arrow">▼</span>
            </a>
            <div class="dropdown-menu dropdown-menu-wide">
                <div class="dropdown-grid">
                    <div class="dropdown-section">
                        <div class="dropdown-section-title">Nổi bật</div>
                        <?php 
                        $popularBrands = array_slice($brands, 0, 6);
                        foreach ($popularBrands as $brand): 
                        ?>
                            <a href="index.php?c=home&a=index&brand=<?php echo $brand['id']; ?>" 
                               class="dropdown-item <?php echo (isset($brandId) && $brandId == $brand['id']) ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($brand['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="dropdown-section">
                        <div class="dropdown-section-title">A-Z</div>
                        <?php foreach ($brands as $brand): ?>
                            <a href="index.php?c=home&a=index&brand=<?php echo $brand['id']; ?>" 
                               class="dropdown-item">
                                <?php echo htmlspecialchars($brand['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <a href="index.php?c=product&a=index" class="menu-item <?php echo (isset($_GET['c']) && $_GET['c'] == 'product' && (!isset($_GET['a']) || $_GET['a'] == 'index')) ? 'active' : ''; ?>">
            SẢN PHẨM
        </a>
        
        <a href="index.php?c=home&a=index&price=1" class="menu-item menu-item-outlet">
            OUTLET / GIÁ RẺ
        </a>

        <a href="#footer" class="menu-item" onclick="document.querySelector('footer').scrollIntoView({behavior: 'smooth'});">
            LIÊN HỆ
        </a>
    </div>
</div>

<section class="hero">
    <div class="hero-left">
        <p class="shop-badge">Sports Hub</p>
        <h1 class="hero-title">Thiết bị thể thao chuẩn cho mọi mục tiêu.</h1>
        <div class="hero-sub">
            Bộ sưu tập mới nhất từ Yonex, Nike, Adidas... Giao nhanh trong 48h, hỗ trợ đổi trả 7 ngày.
        </div>
        <div class="hero-tags">
            <span class="hero-tag">Vợt cầu lông</span>
            <span class="hero-tag">Phụ kiện bảo hộ</span>
            <span class="hero-tag">Giày tập gym</span>
            <span class="hero-tag">Combo ưu đãi</span>
        </div>
        <div class="hero-cta">
            <button class="btn-primary"
                    onclick="document.getElementById('filterForm')?.scrollIntoView({behavior: 'smooth'});">
                Mua ngay
            </button>
            <button class="btn-outline"
                    onclick="document.getElementById('filterForm')?.scrollIntoView({behavior: 'smooth'});">
                Khám phá cửa hàng
            </button>
        </div>
        <div class="shop-stats hero-stats">
            <div>
                <strong>1.200+</strong>
                <span>Khách hàng hài lòng</span>
            </div>
            <div>
                <strong>72h</strong>
                <span>Đổi trả linh hoạt</span>
            </div>
            <div>
                <strong>100%</strong>
                <span>Sản phẩm chính hãng</span>
            </div>
        </div>
    </div>

    <div class="hero-right">
        <?php
        $heroProducts = !empty($bestSellingProducts)
            ? $bestSellingProducts
            : ($featuredProducts ?? []);
        ?>

        <?php if (!empty($heroProducts)): ?>
            <div class="hero-carousel" id="heroCarousel">
                <div class="carousel-track" id="carouselTrack">
                    <?php foreach ($heroProducts as $i => $p): ?>
                        <div class="carousel-slide" data-index="<?php echo $i; ?>">
                            <div class="carousel-card" onclick="window.location='index.php?c=product&a=detail&id=<?php echo $p['id']; ?>'">
                                <?php if (!empty($p['image'])): ?>
                                    <div class="carousel-image">
                                        <img src="<?php echo htmlspecialchars($p['image']); ?>"
                                            alt="<?php echo htmlspecialchars($p['title']); ?>">
                                    </div>
                                <?php else: ?>
                                    <div class="carousel-image carousel-image-placeholder"></div>
                                <?php endif; ?>
                                
                                <div class="carousel-content">
                                    <div class="carousel-title"><?php echo htmlspecialchars($p['title']); ?></div>
                                    <div class="carousel-meta">
                                        <?php
                                            $meta = $p['brand_name'] ?? '';
                                            if (!empty($p['category_name'])) {
                                                $meta .= ($meta ? ' · ' : '') . $p['category_name'];
                                            }
                                            echo htmlspecialchars($meta);
                                        ?>
                                    </div>
                                    <div class="carousel-price"><?php echo number_format($p['price']); ?>đ</div>
                                    <button class="carousel-btn"></button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Controls -->
                <button class="carousel-control carousel-prev" id="carouselPrev">
                    <span>‹</span>
                </button>
                <button class="carousel-control carousel-next" id="carouselNext">
                    <span>›</span>
                </button>

                <!-- Indicators -->
                <div class="carousel-indicators" id="carouselIndicators">
                    <?php foreach ($heroProducts as $i => $p): ?>
                        <button class="carousel-dot <?php echo $i === 0 ? 'active' : ''; ?>" data-slide="<?php echo $i; ?>"></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <script>
                (function() {
                    const carousel = document.getElementById('heroCarousel');
                    const track = document.getElementById('carouselTrack');
                    const slides = Array.from(track.querySelectorAll('.carousel-slide'));
                    const prevBtn = document.getElementById('carouselPrev');
                    const nextBtn = document.getElementById('carouselNext');
                    const indicators = Array.from(document.querySelectorAll('.carousel-dot'));
                    
                    let currentIndex = 0;
                    let autoplayInterval;
                    
                    function updateCarousel() {
                        track.style.transform = `translateX(-${currentIndex * 100}%)`;
                        indicators.forEach((dot, i) => {
                            dot.classList.toggle('active', i === currentIndex);
                        });
                    }
                    
                    function nextSlide() {
                        currentIndex = (currentIndex + 1) % slides.length;
                        updateCarousel();
                        resetAutoplay();
                    }
                    
                    function prevSlide() {
                        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                        updateCarousel();
                        resetAutoplay();
                    }
                    
                    function goToSlide(index) {
                        currentIndex = index;
                        updateCarousel();
                        resetAutoplay();
                    }
                    
                    function startAutoplay() {
                        autoplayInterval = setInterval(nextSlide, 4000);
                    }
                    
                    function resetAutoplay() {
                        clearInterval(autoplayInterval);
                        startAutoplay();
                    }
                    
                    nextBtn.addEventListener('click', nextSlide);
                    prevBtn.addEventListener('click', prevSlide);
                    indicators.forEach((dot, i) => {
                        dot.addEventListener('click', () => goToSlide(i));
                    });
                    
                    startAutoplay();
                })();
            </script>
        <?php endif; ?>
    </div>


</section>

<div class="page-shell">

    <?php 
    $hasFilter = ($categoryId ?? 0) > 0 || ($brandId ?? 0) > 0 || ($priceRange ?? 'all') !== 'all' || !empty($keyword ?? '');
    $productCount = isset($products) ? count($products) : 0;
    ?>

    <?php if (!$hasFilter && (!empty($featuredProducts) || !empty($bestSellingProducts))): ?>
        <!-- Hiển thị sections gợi ý và bán chạy khi không có filter -->
        <div class="suggest-layout">
            <aside class="suggest-sidebar">
                <form id="filterForm" method="get" action="index.php">
                    <input type="hidden" name="c" value="home">
                    <input type="hidden" name="a" value="index">

                    <div class="filter-group">
                        <h3>Danh mục</h3>
                        <div class="filter-options">
                            <label>
                                <input type="radio" name="category" value="0"
                                       <?php echo (($categoryId ?? 0) == 0) ? 'checked' : ''; ?>>
                                Tất cả
                            </label>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <label>
                                        <input type="radio"
                                               name="category"
                                               value="<?php echo $cat['id']; ?>"
                                            <?php echo (($categoryId ?? 0) == $cat['id']) ? 'checked' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="filter-group">
                        <h3>Thương hiệu</h3>
                        <div class="filter-options">
                            <label>
                                <input type="radio" name="brand" value="0"
                                       <?php echo (($brandId ?? 0) == 0) ? 'checked' : ''; ?>>
                                Tất cả
                            </label>
                            <?php if (!empty($brands)): ?>
                                <?php foreach ($brands as $b): ?>
                                    <label>
                                        <input type="radio"
                                               name="brand"
                                               value="<?php echo $b['id']; ?>"
                                            <?php echo (($brandId ?? 0) == $b['id']) ? 'checked' : ''; ?>>
                                        <?php echo htmlspecialchars($b['name']); ?>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="filter-group">
                        <h3>Khoảng giá</h3>
                        <div class="filter-options">
                            <label>
                                <input type="radio" name="price" value="all"
                                       <?php echo (($priceRange ?? 'all') === 'all') ? 'checked' : ''; ?>>
                                Tất cả
                            </label>
                            <label>
                                <input type="radio" name="price" value="1"
                                       <?php echo (($priceRange ?? 'all') === '1') ? 'checked' : ''; ?>>
                                Dưới 500k
                            </label>
                            <label>
                                <input type="radio" name="price" value="2"
                                       <?php echo (($priceRange ?? 'all') === '2') ? 'checked' : ''; ?>>
                                500k - 1.5tr
                            </label>
                            <label>
                                <input type="radio" name="price" value="3"
                                       <?php echo (($priceRange ?? 'all') === '3') ? 'checked' : ''; ?>>
                                Trên 1.5tr
                            </label>
                        </div>
                    </div>
                </form>
            </aside>

            <div class="suggest-content">
                <section class="view-section">
                    <div class="section-header">
                        <div>
                            <div class="section-title">Gợi ý cho bạn</div>
                            <div class="section-sub">Một vài sản phẩm nổi bật từ cửa hàng.</div>
                        </div>
                    </div>

                    <div class="chip-row">
                        <button class="chip active" data-cat="all">Tất cả</button>
                        <button class="chip" data-cat="Vợt">Vợt</button>
                        <button class="chip" data-cat="Giày">Giày</button>
                        <button class="chip" data-cat="Phụ kiện">Phụ kiện</button>
                    </div>

                    <div class="product-grid" id="suggestGrid">
                        <?php if (!empty($featuredProducts)): ?>
                            <?php foreach ($featuredProducts as $p): ?>
                                <?php
                                $catName = $p['category_name'] ?? '';
                                $categorySlug = $catName ? slugify_vi($catName) : 'khac';
                                ?>
                                <a class="product-card"
                                    data-category="<?php echo htmlspecialchars($p['category_name'] ?? ''); ?>"
                                    data-category-slug="<?php echo htmlspecialchars($categorySlug); ?>"
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
                </section>

                <section class="view-section">
                    <div class="section-header">
                        <div>
                            <div class="section-title">Sản phẩm bán chạy</div>
                            <div class="section-sub">
                                Dựa trên số lượng đã bán trong bảng PRODUCT.
                            </div>
                        </div>
                    </div>

                    <div class="product-grid">
                        <?php if (!empty($bestSellingProducts)): ?>
                            <?php foreach ($bestSellingProducts as $p): ?>
                                <?php
                                $catName = $p['category_name'] ?? '';
                                $categorySlug = $catName ? slugify_vi($catName) : 'khac';
                                ?>
                                <a class="product-card"
                                    data-category="<?php echo htmlspecialchars($p['category_name'] ?? ''); ?>"
                                    data-category-slug="<?php echo htmlspecialchars($categorySlug); ?>"
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
                                        <div class="product-stock">
                                            Đã bán: <?php echo (int)($p['number_sold'] ?? 0); ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Chưa có dữ liệu bán chạy.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </div>
    <?php endif; ?>

    <!-- Phần hiển thị tất cả sản phẩm với filter -->
    <section class="view-section">
        <div class="section-header">
            <div>
                <div class="section-title"><?php echo $hasFilter ? 'Kết quả tìm kiếm' : 'Tất cả sản phẩm'; ?></div>
                <div class="section-sub">
                    <?php echo $hasFilter ? 'Danh sách sản phẩm theo bộ lọc của bạn.' : 'Lọc theo danh mục, nhãn hiệu, khoảng giá...'; ?>
                </div>
            </div>
        </div>

        <div class="products-layout">
            <!-- LIST PRODUCT CAROUSEL -->
            <div>
                <div class="product-toolbar">
                    <div class="product-count">
                        <?php echo $productCount; ?> sản phẩm
                    </div>
                </div>

                <div class="products-carousel-container">
                    <button class="carousel-btn carousel-prev" onclick="scrollCarousel('left')">‹</button>
                    
                    <div class="products-carousel-track">
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $p): ?>
                                <a class="product-carousel-card"
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

                    <button class="carousel-btn carousel-next" onclick="scrollCarousel('right')">›</button>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Products Carousel
    function scrollCarousel(direction) {
        const track = document.querySelector('.products-carousel-track');
        const scrollAmount = 280; // card width + gap
        if (direction === 'left') {
            track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }

    // Auto-scroll carousel
    setInterval(() => {
        const track = document.querySelector('.products-carousel-track');
        if (track) {
            const maxScroll = track.scrollWidth - track.clientWidth;
            if (track.scrollLeft >= maxScroll) {
                track.scrollLeft = 0;
            } else {
                scrollCarousel('right');
            }
        }
    }, 5000);

    (function () {
        // Mobile dropdown toggle
        const dropdownItems = document.querySelectorAll('.menu-item.dropdown');
        const handleDropdown = () => {
            if (window.innerWidth <= 900) {
                dropdownItems.forEach(item => {
                    const link = item.querySelector('.menu-link');
                    if (link) {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            // Close other dropdowns
                            dropdownItems.forEach(other => {
                                if (other !== item) {
                                    other.classList.remove('active');
                                }
                            });
                            item.classList.toggle('active');
                        });
                    }
                });
            } else {
                // Remove click handlers on desktop
                dropdownItems.forEach(item => {
                    const link = item.querySelector('.menu-link');
                    if (link) {
                        link.replaceWith(link.cloneNode(true));
                    }
                });
            }
        };
        
        handleDropdown();
        window.addEventListener('resize', handleDropdown);
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 900) {
                if (!e.target.closest('.menu-item.dropdown')) {
                    dropdownItems.forEach(item => {
                        item.classList.remove('active');
                    });
                }
            }
        });
        
        // Filter form auto-submit
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.querySelectorAll('input[type="radio"]').forEach(input => {
                input.addEventListener('change', () => filterForm.submit());
            });
        }

        // Chip filter for featured products
        const chips = document.querySelectorAll('.chip-row .chip');
        const cards = document.querySelectorAll('#suggestGrid .product-card');
        if (chips.length && cards.length) {
            chips.forEach(chip => {
                chip.addEventListener('click', () => {
                    chips.forEach(c => c.classList.remove('active'));
                    chip.classList.add('active');

                    const cat = chip.dataset.cat || 'all';
                    cards.forEach(card => {
                        const cardCat = card.dataset.category || '';
                        if (cat === 'all' || cardCat === cat) {
                            card.classList.remove('hidden');
                        } else {
                            card.classList.add('hidden');
                        }
                    });
                });
            });
        }
    })();
</script>

<footer>
    <div class="footer-container">
        <div>
            <h4>Về Sports Hub</h4>
            <ul>
                <li><a href="#">Giới thiệu</a></li>
                <li><a href="#">Tuyên bố chính sách</a></li>
                <li><a href="#">Điều khoản dịch vụ</a></li>
                <li><a href="#">Chính sách bảo mật</a></li>
            </ul>
        </div>
        <div>
            <h4>Mua sắm</h4>
            <ul>
                <li><a href="index.php?c=home&a=index">Trang chủ</a></li>
                <li><a href="index.php?c=product&a=index">Cửa hàng</a></li>
                <li><a href="index.php?c=home&a=index&price=1">Outlet</a></li>
                <li><a href="index.php?c=product&a=cart">Giỏ hàng</a></li>
            </ul>
        </div>
        <div>
            <h4>Hỗ trợ</h4>
            <ul>
                <li><a href="#">Liên hệ chúng tôi</a></li>
                <li><a href="#">Câu hỏi thường gặp</a></li>
                <li><a href="#">Hướng dẫn mua hàng</a></li>
                <li><a href="#">Theo dõi đơn hàng</a></li>
            </ul>
        </div>
        <div>
            <h4>Kết nối</h4>
            <p style="font-size: 13px; color: #d1d5db; margin-bottom: 12px;">Theo dõi chúng tôi trên mạng xã hội</p>
            <div class="footer-socials">
                <a href="#" class="social-icon" title="Facebook">f</a>
                <a href="#" class="social-icon" title="Instagram">📷</a>
                <a href="#" class="social-icon" title="Twitter">𝕏</a>
                <a href="#" class="social-icon" title="YouTube">▶</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p style="margin: 0;">© 2025 Sports Hub. Tất cả quyền được bảo lưu. | Được thiết kế bằng ❤️</p>
        <p style="margin: 5px 0 0 0; font-size: 11px; color: #ffffffff;">Giao diện khách hàng – dữ liệu từ MySQL (bảng PRODUCT).</p>
    </div>
</footer>