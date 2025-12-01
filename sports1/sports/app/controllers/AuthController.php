<?php
// hello pvl
require_once __DIR__ . '/../core/Controller.php'; 

class AuthController extends Controller
{
    public function login()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($username === '' || $password === '') {
                $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
            } else {
                $accountModel = $this->loadModel('Account');
                $account = $accountModel->findByUsernameAndPassword($username, $password);

                if ($account) {
                    $_SESSION['account'] = [
                        'id'       => $account['id'],
                        'username' => $account['username'],
                        'role'     => $account['role'],
                        'email'    => $account['email'] ?? '',
                    ];

                    if ($account['role'] === 'ADMIN') {
                        $this->redirect('admin.php?c=dashboard&a=index');
                    } else {
                        $this->redirect('index.php?c=home&a=index');
                    }
                } else {
                    $error = 'Sai tên đăng nhập hoặc mật khẩu.';
                }
            }
        }

        $this->render('auth/login', [
            'error' => $error,
        ]);
    }

 public function logout()
{
    // 1. Xóa thông tin tài khoản (Đăng xuất)
    unset($_SESSION['account']);
    
    // 2. THÊM DÒNG NÀY: Xóa giỏ hàng (nếu bạn lưu giỏ hàng trong $_SESSION['cart'])
    unset($_SESSION['cart']); 

    // Tùy chọn 3. Xóa toàn bộ Session (Cần thiết nếu bạn muốn xóa mọi thứ)
    // session_destroy(); 

    $this->redirect('index.php?c=home&a=index');
}

    public function register()
    {
        // ... (Logic POST đăng ký sẽ được thêm vào đây)
        
        $this->render('auth/register', [
            'pageTitle' => 'Đăng ký tài khoản'
        ]);
    }

    public function handleRegister()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $name     = trim($_POST['name'] ?? '');
            $phone    = trim($_POST['phone'] ?? '');
            $address  = trim($_POST['address'] ?? '');

            if ($username === '' || $password === '' || $email === '' || $name === '') {
                $error = 'Vui lòng nhập đầy đủ thông tin bắt buộc.';
            } else {
                $accountModel = $this->loadModel('Account');

                if ($accountModel->existsByUsername($username)) {
                    $error = 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.';
                } elseif ($accountModel->existsByEmail($email)) {
                    $error = 'Email đã được sử dụng. Vui lòng dùng email khác.';
                } else {
                    $data = [
                        'username' => $username,
                        'password' => $password,
                        'email'    => $email,
                        'name'     => $name,
                        'phone'    => $phone,
                        'address'  => $address,
                        'role'     => 'USER',
                    ];

                    $newId = $accountModel->createAccountWithProfile($data);
                    if ($newId) {
                        // Redirect to login page after successful registration
                        $this->redirect('index.php?c=auth&a=login');
                        return;
                    } else {
                        $error = 'Lỗi khi tạo tài khoản. Vui lòng thử lại.';
                    }
                }
            }
        }

        // Nếu có lỗi, render lại form đăng ký (register.php sẽ giữ giá trị cũ từ $_POST)
        $this->render('auth/register', [
            'error' => $error,
            'pageTitle' => 'Đăng ký tài khoản'
        ]);
    }
    
    public function forgotPassword()
    {
        $data = ['error' => '', 'success' => '', 'email' => '', 'decryptedPassword' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $data['email'] = $email;

            if (empty($email)) {
                $data['error'] = 'Vui lòng nhập Email.';
            } else {
                $accountModel = $this->loadModel('Account');
                $account = $accountModel->findByEmail($email);

                if ($account) {
                    $decryptedPassword = $account['decryptedPassword'] ?? 'Không tìm thấy mật khẩu.'; 
                    $data['success'] = 'Tìm thấy tài khoản. Mật khẩu của bạn là:';
                    $data['decryptedPassword'] = $decryptedPassword;
                } else {
                    $data['error'] = 'Không tìm thấy tài khoản nào với Email này.';
                }
            }
        }

        $this->render('auth/forgot_password', $data);
    }
}