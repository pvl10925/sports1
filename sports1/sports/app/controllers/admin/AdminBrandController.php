<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Brand.php';

class AdminBrandController extends Controller
{
    public function index()
    {
        $model  = new Brand();
        $brands = $model->getAll();

        $this->render(
            'admin/brand/index',
            [
                'pageTitle' => 'Quản lý nhãn hiệu',
                'brands'    => $brands,
            ],
            'admin'
        );
    }

    public function create()
    {
        $model = new Brand();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $desc = trim($_POST['description'] ?? '');

            if ($name === '') {
                $error = 'Tên nhãn hiệu không được để trống.';
            } else {
                $model->create([
                    'name'        => $name,
                    'description' => $desc,
                ]);
                $this->redirect('admin.php?c=brand&a=index');
            }
        }

        $this->render(
            'admin/brand/create',
            [
                'pageTitle' => 'Thêm nhãn hiệu',
                'error'     => $error,
            ],
            'admin'
        );
    }
}
