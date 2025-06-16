<?php
require_once __DIR__ . '/../models/DangKy.php';
require_once __DIR__ . '/../models/HocPhan.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

class DangKyController
{
    private $dangKyModel;
    private $hocPhanModel;

    public function __construct()
    {
        $this->dangKyModel = new DangKy();
        $this->hocPhanModel = new HocPhan();
    }

    // Hiển thị giỏ hàng (học phần đã chọn)
    public function cart()
    {
        if (!isLoggedIn()) {
            redirect('controllers/AuthController.php?action=showLogin');
        }

        $maSV = getCurrentUser()['MaSV'];
        $cart = getCart();
        $danhSachHP = $this->hocPhanModel->getByIds($cart);

        include __DIR__ . '/../views/dangky/cart.php';
    }

    // Xóa một học phần khỏi giỏ hàng
    public function removeFromCart()
    {
        if (!isLoggedIn()) {
            redirect('controllers/AuthController.php?action=showLogin');
        }

        $maHP = $_GET['id'] ?? '';
        if (empty($maHP)) {
            redirect('controllers/DangKyController.php?action=cart');
        }

        if (removeFromCart($maHP)) {
            redirect('controllers/DangKyController.php?action=cart', 'Đã xóa học phần khỏi giỏ hàng!', 'success');
        } else {
            redirect('controllers/DangKyController.php?action=cart', 'Không thể xóa học phần!', 'error');
        }
    }

    // Xóa toàn bộ đăng ký (giỏ hàng)
    public function clearCart()
    {
        if (!isLoggedIn()) {
            redirect('controllers/AuthController.php?action=showLogin');
        }

        clearCart();
        redirect('controllers/DangKyController.php?action=cart', 'Đã xóa toàn bộ giỏ hàng!', 'success');
    }

    // Lưu đăng ký học phần
    public function saveRegistration()
    {
        if (!isLoggedIn()) {
            redirect('controllers/AuthController.php?action=showLogin');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('controllers/DangKyController.php?action=cart');
        }

        $maSV = getCurrentUser()['MaSV'];
        $cart = getCart();

        if (empty($cart)) {
            redirect('controllers/DangKyController.php?action=cart', 'Giỏ hàng trống!', 'warning');
        }

        try {
            // Kiểm tra số lượng còn lại trước khi lưu
            $danhSachHP = $this->hocPhanModel->getByIds($cart);
            foreach ($danhSachHP as $hp) {
                if ($hp['SoLuongConLai'] <= 0) {
                    redirect('controllers/DangKyController.php?action=cart', 'Học phần ' . $hp['TenHP'] . ' đã hết slot!', 'error');
                }
            }

            // Lưu đăng ký
            $maDK = $this->dangKyModel->create($maSV, $cart);

            // Cập nhật số lượng đã đăng ký
            foreach ($cart as $maHP) {
                $this->hocPhanModel->updateSoLuongDaDangKy($maHP, 1);
            }

            clearCart();
            redirect('controllers/DangKyController.php?action=cart', 'Đăng ký thành công!', 'success');
        } catch (Exception $e) {
            redirect('controllers/DangKyController.php?action=cart', 'Có lỗi xảy ra khi lưu đăng ký!', 'error');
        }
    }
}

// Xử lý request
$action = $_GET['action'] ?? 'cart';
$controller = new DangKyController();

switch ($action) {
    case 'cart':
        $controller->cart();
        break;
    case 'removeFromCart':
        $controller->removeFromCart();
        break;
    case 'clearCart':
        $controller->clearCart();
        break;
    case 'saveRegistration':
        $controller->saveRegistration();
        break;
    default:
        $controller->cart();
        break;
}
