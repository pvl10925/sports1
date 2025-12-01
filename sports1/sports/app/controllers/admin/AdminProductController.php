<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Brand.php';
require_once __DIR__ . '/../../models/Category.php';

class AdminProductController extends Controller
{
    public function index()
    {
        $model    = new Product();
        $products = $model->getAll();

        $this->render(
            'admin/product/index',
            [
                'products'  => $products,
                'pageTitle' => 'Quản lý sản phẩm',
            ],
            'admin'
        );
    }

    public function create()
    {
        $productModel  = new Product();
        $brandModel    = new Brand();
        $categoryModel = new Category();

        $brands     = $brandModel->getAll();
        $categories = $categoryModel->getAll();
        $error      = null;
        $product    = null;   // không có dữ liệu cũ
        $mode       = 'create';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $brandId     = (int)($_POST['brand_id'] ?? 0);
            $categoryId  = (int)($_POST['category_id'] ?? 0);
            $price       = (float)($_POST['price'] ?? 0);
            $stock       = (int)($_POST['stock'] ?? 0);
            $datePub     = $_POST['date_publication'] ?? null;
            $description = $_POST['description'] ?? null;

            if ($title === '' || !$brandId || !$categoryId || $price <= 0) {
                $error = 'Vui lòng nhập đầy đủ Tên, Thương hiệu, Danh mục và Giá > 0.';

                // giữ lại dữ liệu user nhập (dùng lại trong form)
                $product = [
                    'title'           => $title,
                    'brand_id'        => $brandId,
                    'category_id'     => $categoryId,
                    'price'           => $price,
                    'number_in_stock' => $stock,
                    'date_publication'=> $datePub,
                    'description'     => $description,
                    'image'           => null,
                ];
            } else {
                try {
                    // upload ảnh mới, không có oldPath
                    $imagePath = $this->handleImageUpload(null);

                    $productModel->create([
                        'brand_id'        => $brandId,
                        'category_id'     => $categoryId,
                        'title'           => $title,
                        'price'           => $price,
                        'stock'           => $stock,
                        'date_publication'=> $datePub,
                        'description'     => $description,
                        'image'           => $imagePath,
                    ]);

                    $this->redirect('admin.php?c=product&a=index');
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                    $product = [
                        'title'           => $title,
                        'brand_id'        => $brandId,
                        'category_id'     => $categoryId,
                        'price'           => $price,
                        'number_in_stock' => $stock,
                        'date_publication'=> $datePub,
                        'description'     => $description,
                        'image'           => null,
                    ];
                }
            }
        }

        $this->render(
            'admin/product/form',
            [
                'pageTitle'  => 'Thêm sản phẩm',
                'product'    => $product,
                'brands'     => $brands,
                'categories' => $categories,
                'error'      => $error,
                'mode'       => $mode,
            ],
            'admin'
        );
    }

    public function edit()
    {
        $productModel  = new Product();
        $brandModel    = new Brand();
        $categoryModel = new Category();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            $this->redirect('admin.php?c=product&a=index');
        }

        $product = $productModel->find($id);
        if (!$product) {
            $this->redirect('admin.php?c=product&a=index');
        }

        $brands     = $brandModel->getAll();
        $categories = $categoryModel->getAll();
        $error      = null;
        $mode       = 'edit';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $brandId     = (int)($_POST['brand_id'] ?? 0);
            $categoryId  = (int)($_POST['category_id'] ?? 0);
            $price       = (float)($_POST['price'] ?? 0);
            $stock       = (int)($_POST['stock'] ?? 0);
            $datePub     = $_POST['date_publication'] ?? null;
            $description = $_POST['description'] ?? null;

            if ($title === '' || !$brandId || !$categoryId || $price <= 0) {
                $error = 'Vui lòng nhập đầy đủ Tên, Thương hiệu, Danh mục và Giá > 0.';

                // cập nhật lại dữ liệu đang sửa để fill lại form
                $product = array_merge($product, [
                    'title'           => $title,
                    'brand_id'        => $brandId,
                    'category_id'     => $categoryId,
                    'price'           => $price,
                    'number_in_stock' => $stock,
                    'date_publication'=> $datePub,
                    'description'     => $description,
                ]);
            } else {
                try {
                    // nếu không chọn file mới → giữ ảnh cũ
                    $imagePath = $this->handleImageUpload($product['image'] ?? null);

                    $productModel->update($id, [
                        'brand_id'        => $brandId,
                        'category_id'     => $categoryId,
                        'title'           => $title,
                        'price'           => $price,
                        'stock'           => $stock,
                        'date_publication'=> $datePub,
                        'description'     => $description,
                        'image'           => $imagePath,
                    ]);

                    $this->redirect('admin.php?c=product&a=index');
                } catch (\Exception $e) {
                    $error = $e->getMessage();

                    $product = array_merge($product, [
                        'title'           => $title,
                        'brand_id'        => $brandId,
                        'category_id'     => $categoryId,
                        'price'           => $price,
                        'number_in_stock' => $stock,
                        'date_publication'=> $datePub,
                        'description'     => $description,
                    ]);
                }
            }
        }

        $this->render(
            'admin/product/form',
            [
                'pageTitle'  => 'Sửa sản phẩm',
                'product'    => $product,
                'brands'     => $brands,
                'categories' => $categories,
                'error'      => $error,
                'mode'       => $mode,
            ],
            'admin'
        );
    }

    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $productModel = new Product();
            $productModel->delete($id); // tạm bỏ qua FK như bạn nói
        }
        $this->redirect('admin.php?c=product&a=index');
    }

    /**
     * Upload ảnh sản phẩm, trả về đường dẫn tương đối để lưu DB
     * (ví dụ: uploads/products/abc.jpg)
     */
    private function handleImageUpload(?string $oldPath = null): ?string
    {
        // Không chọn file mới → dùng lại đường dẫn cũ (edit) hoặc null (create)
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            return $oldPath;
        }

        $file = $_FILES['image'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Lỗi upload ảnh (mã lỗi: ' . $file['error'] . ')');
        }

        // Giới hạn loại file
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!isset($allowed[$mime])) {
            throw new \Exception('Chỉ cho phép upload JPG, PNG, WEBP');
        }

        $ext = $allowed[$mime];

        // Thư mục lưu file vật lý
        $uploadDir = __DIR__ . '/../../../public/uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Tạo tên file duy nhất
        $fileName = uniqid('prod_', true) . '.' . $ext;
        $target   = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new \Exception('Không thể lưu file ảnh lên server');
        }

        // Đường dẫn lưu vào DB (tính từ thư mục public)
        return 'uploads/products/' . $fileName;
    }
}
