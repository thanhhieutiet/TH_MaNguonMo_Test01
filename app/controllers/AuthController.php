<?php
require_once __DIR__ . '/../models/SinhVien.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

class AuthController
{
    private $sinhVienModel;

    public function __construct()
    {
        $this->sinhVienModel = new SinhVien();
    }

    // Hiển thị trang đăng nhập
    public function showLogin()
    {
        if (isLoggedIn()) {
            redirect('index.php');
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    // Xử lý đăng nhập
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('controllers/AuthController.php?action=showLogin');
        }

        $maSV = cleanInput($_POST['maSV'] ?? '');
        $password = cleanInput($_POST['password'] ?? '');

        // Validate
        $errors = [];

        if (empty($maSV)) {
            $errors[] = "Vui lòng nhập mã sinh viên";
        }

        if (empty($password)) {
            $errors[] = "Vui lòng nhập mật khẩu";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            redirect('controllers/AuthController.php?action=showLogin');
        }

        // Kiểm tra đăng nhập
        $user = $this->sinhVienModel->login($maSV, $password);

        if ($user) {
            login($user);
            redirect('index.php', 'Đăng nhập thành công!', 'success');
        } else {
            $_SESSION['errors'] = ['Mã sinh viên hoặc mật khẩu không chính xác'];
            $_SESSION['old_input'] = $_POST;
            redirect('controllers/AuthController.php?action=showLogin');
        }
    }

    // Đăng xuất
    public function logout()
    {
        logout();
        redirect('controllers/AuthController.php?action=showLogin', 'Đã đăng xuất thành công!', 'success');
    }
}

// Xử lý request
$action = $_GET['action'] ?? 'showLogin';
$controller = new AuthController();

switch ($action) {
    case 'showLogin':
        $controller->showLogin();
        break;
    case 'login':
        $controller->login();
        break;
    case 'logout':
        $controller->logout();
        break;
    default:
        $controller->showLogin();
        break;
}
