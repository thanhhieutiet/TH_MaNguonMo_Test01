<?php
require_once __DIR__ . '/../models/HocPhan.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

class HocPhanController
{
    private $hocPhanModel;

    public function __construct()
    {
        $this->hocPhanModel = new HocPhan();
    }

    // Danh sách học phần để đăng ký
    public function index()
    {
        if (!isLoggedIn()) {
            redirect('controllers/AuthController.php?action=showLogin');
        }

        $hocPhans = $this->hocPhanModel->getAll();
        $cart = getCart();

        include __DIR__ . '/../views/hocphan/index.php';
    }

    // Thêm học phần vào giỏ hàng
    public function addToCart()
    {
        if (!isLoggedIn()) {
            redirect('controllers/AuthController.php?action=showLogin');
        }

        $maHP = $_GET['id'] ?? '';
        if (empty($maHP)) {
            redirect('controllers/HocPhanController.php?action=index');
        }

        // Kiểm tra học phần có tồn tại và còn slot không
        $hocPhan = $this->hocPhanModel->getById($maHP);
        if (!$hocPhan) {
            redirect('controllers/HocPhanController.php?action=index', 'Học phần không tồn tại!', 'error');
        }

        if ($hocPhan['SoLuongConLai'] <= 0) {
            redirect('controllers/HocPhanController.php?action=index', 'Học phần đã hết slot!', 'error');
        }

        // Thêm vào giỏ hàng
        if (addToCart($maHP)) {
            redirect('controllers/HocPhanController.php?action=index', 'Đã thêm học phần vào giỏ hàng!', 'success');
        } else {
            redirect('controllers/HocPhanController.php?action=index', 'Học phần đã có trong giỏ hàng!', 'warning');
        }
    }
}

// Xử lý request
$action = $_GET['action'] ?? 'index';
$controller = new HocPhanController();

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'addToCart':
        $controller->addToCart();
        break;
    default:
        $controller->index();
        break;
}
