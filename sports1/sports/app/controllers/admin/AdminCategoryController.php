<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Category.php';

class AdminCategoryController extends Controller
{
    public function index()
    {
        $model      = new Category();
        $categories = $model->getAll();

        $this->render(
            'admin/category/index',
            [
                'pageTitle'  => 'Quản lý danh mục',
                'categories' => $categories,
            ],
            'admin'
        );
    }

    public function create()
    {
        $model = new Category();
        $error = null;
        $category = null; // không có dữ liệu cũ

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');

            if ($name === '') {
                $error = 'Tên danh mục không được để trống.';
            } else {
                $model->create(['name' => $name]);
                $this->redirect('admin.php?c=category&a=index');
            }
        }

        $this->render(
            'admin/category/form',
            [
                'pageTitle' => 'Thêm danh mục',
                'error'     => $error,
                'category'  => $category,
                'mode'      => 'create',
            ],
            'admin'
        );
    }

    public function edit()
    {
        $model = new Category();
        $error = null;

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            $this->redirect('admin.php?c=category&a=index');
        }

        $category = $model->find($id);
        if (!$category) {
            $this->redirect('admin.php?c=category&a=index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');

            if ($name === '') {
                $error = 'Tên danh mục không được để trống.';
            } else {
                $model->update($id, ['name' => $name]);
                $this->redirect('admin.php?c=category&a=index');
            }
        }

        $this->render(
            'admin/category/form',
            [
                'pageTitle' => 'Sửa danh mục',
                'error'     => $error,
                'category'  => $category, // có dữ liệu cũ
                'mode'      => 'edit',
            ],
            'admin'
        );
    }

    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $model = new Category();
            $model->delete($id);
        }
        $this->redirect('admin.php?c=category&a=index');
    }
}
