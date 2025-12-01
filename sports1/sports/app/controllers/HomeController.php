<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Brand.php';
require_once __DIR__ . '/../models/Category.php';

class HomeController extends Controller
{
    public function index()
    {
        $productModel  = new Product();
        $brandModel    = new Brand();
        $categoryModel = new Category();

        // Lấy các option filter
        $categoryId  = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $brandId     = isset($_GET['brand'])    ? (int)$_GET['brand']    : 0;
        $priceRange  = isset($_GET['price'])    ? $_GET['price']         : 'all';
        $keyword     = trim($_GET['q'] ?? '');

        // Lấy tất cả sản phẩm với filter
        $products   = $productModel->getFiltered($categoryId, $brandId, $priceRange, $keyword);
        $categories = $categoryModel->getAll();
        $brands     = $brandModel->getAll();

        // Gợi ý (mới nhất) - chỉ hiển thị khi không có filter
        $featuredProducts = [];
        $bestSellingProducts = [];
        if ($categoryId == 0 && $brandId == 0 && $priceRange == 'all' && empty($keyword)) {
            $featuredProducts = $productModel->getFeatured(6);
            $bestSellingProducts = $productModel->getBestSelling(4);
        }

        $this->render(
            'home/index',
            [
                'pageTitle'          => 'Sports Hub - Trang chủ',
                'products'            => $products,
                'categories'          => $categories,
                'brands'              => $brands,
                'categoryId'          => $categoryId,
                'brandId'             => $brandId,
                'priceRange'          => $priceRange,
                'keyword'             => $keyword,
                'featuredProducts'   => $featuredProducts,
                'bestSellingProducts'=> $bestSellingProducts,
            ],
            'main'
        );
    }
}
