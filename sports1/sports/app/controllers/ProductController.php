<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Brand.php';
require_once __DIR__ . '/../models/Category.php';

class ProductController extends Controller
{
    public function index()
    {
        $productModel  = new Product();
        $brandModel    = new Brand();
        $categoryModel = new Category();

        // lấy các option filter
        $categoryId  = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $brandId     = isset($_GET['brand'])    ? (int)$_GET['brand']    : 0;
        $priceRange  = isset($_GET['price'])    ? $_GET['price']         : 'all';
        $keyword     = trim($_GET['q'] ?? '');

        $products   = $productModel->getFiltered($categoryId, $brandId, $priceRange, $keyword);
        $categories = $categoryModel->getAll();
        $brands     = $brandModel->getAll();

        $this->render('product/index', [
            'products'     => $products,
            'categories'   => $categories,
            'brands'       => $brands,
            'categoryId'   => $categoryId,
            'brandId'      => $brandId,
            'priceRange'   => $priceRange,
            'keyword'      => $keyword,
        ]);
    }

    public function detail()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            http_response_code(404);
            echo "Sản phẩm không tồn tại.";
            return;
        }

        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            http_response_code(404);
            echo "Sản phẩm không tồn tại.";
            return;
        }

        $this->render('product/detail', [
            'product'   => $product,
            'pageTitle' => $product['title'] . ' - Sports Hub',
        ]);
    }

    public function addToCart()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=product&a=index');
        }

        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity  = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $quantity  = max(1, min($quantity, 99));

        if ($productId <= 0) {
            $this->redirect('index.php?c=product&a=index');
        }

        $productModel = new Product();
        $product = $productModel->find($productId);
        if (!$product) {
            $this->redirect('index.php?c=product&a=index');
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'id'       => $product['id'],
                'title'    => $product['title'],
                'price'    => $product['price'],
                'image'    => $product['image'],
                'quantity' => $quantity,
            ];
        }

        $this->redirect('index.php?c=product&a=detail&id=' . $productId . '&added=1');
    }

    public function cart()
    {
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        $this->render('product/cart', [
            'cartItems' => $cart,
            'cartTotal' => $total,
            'pageTitle' => 'Giỏ hàng - Sports Hub',
        ]);
    }

    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=product&a=cart');
        }

        if (empty($_SESSION['cart'])) {
            $this->redirect('index.php?c=product&a=cart');
        }

        $quantities = $_POST['quantity'] ?? [];
        foreach ($quantities as $productId => $qty) {
            $productId = (int)$productId;
            $qty = (int)$qty;

            if (!isset($_SESSION['cart'][$productId])) {
                continue;
            }

            if ($qty <= 0) {
                unset($_SESSION['cart'][$productId]);
                continue;
            }

            $_SESSION['cart'][$productId]['quantity'] = min(99, $qty);
        }

        $this->redirect('index.php?c=product&a=cart&updated=1');
    }

    public function removeFromCart()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=product&a=cart');
        }

        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        if ($productId && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }

        $this->redirect('index.php?c=product&a=cart&removed=1');
    }

    public function clearCart()
    {
        unset($_SESSION['cart']);
        $this->redirect('index.php?c=product&a=cart');
    }

    public function checkout()
    {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $this->redirect('index.php?c=product&a=cart');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        $account = $_SESSION['account'] ?? null;
        $accountModel = $this->loadModel('Account');
        $userData = null;

        // Nếu đã đăng nhập, lấy thông tin user
        if ($account) {
            $userId = $accountModel->getUserIdByAccountId($account['id']);
            if ($userId) {
                $userData = $accountModel->getUserData($userId);
            }
        }

        $this->render('product/checkout', [
            'cartItems' => $cart,
            'cartTotal' => $total,
            'userData' => $userData,
            'pageTitle' => 'Thanh toán - Sports Hub',
        ]);
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=product&a=cart');
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $this->redirect('index.php?c=product&a=cart');
        }

        // Lấy thông tin từ form
        $customerName = trim($_POST['customer_name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        // Validate
        if (empty($customerName) || empty($address) || empty($phone)) {
            $this->redirect('index.php?c=product&a=checkout&error=1');
        }

        // Tính tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        // Lấy user_id
        $account = $_SESSION['account'] ?? null;
        $accountModel = $this->loadModel('Account');
        $userId = null;

        if ($account) {
            // Nếu đã đăng nhập, lấy user_id từ account_id
            $userId = $accountModel->getUserIdByAccountId($account['id']);
        }

        // Nếu chưa có user_id, tạo guest user
        if (!$userId) {
            $userId = $accountModel->createGuestUser($customerName, $phone, $address);
            if (!$userId) {
                $this->redirect('index.php?c=product&a=checkout&error=2');
            }
        }

        // Tạo đơn hàng
        $orderModel = $this->loadModel('Order');
        $orderId = $orderModel->create($userId, $total, $customerName, $address, $phone, $cart);

        if ($orderId) {
            // Xóa giỏ hàng
            unset($_SESSION['cart']);
            // Chuyển đến trang thành công
            $this->redirect('index.php?c=product&a=checkoutSuccess&order_id=' . $orderId);
        } else {
            $this->redirect('index.php?c=product&a=checkout&error=3');
        }
    }

    public function checkoutSuccess()
    {
        $orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
        
        $this->render('product/checkout_success', [
            'orderId' => $orderId,
            'pageTitle' => 'Đặt hàng thành công - Sports Hub',
        ]);
    }
}
