<?php
require_once __DIR__ . '/../models/SinhVien.php';
require_once __DIR__ . '/../models/NganhHoc.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

class SinhVienController
{
    private $sinhVienModel;
    private $nganhHocModel;

    public function __construct()
    {
        $this->sinhVienModel = new SinhVien();
        $this->nganhHocModel = new NganhHoc();
    }

    // Danh sách sinh viên (với phân trang)
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 4; // 4 sinh viên mỗi trang theo yêu cầu

        $sinhViens = $this->sinhVienModel->getAll($page, $limit);
        $totalRecords = $this->sinhVienModel->countAll();
        $pagination = getPagination($page, $totalRecords, $limit, 'controllers/SinhVienController.php?action=index');

        include __DIR__ . '/../views/sinhvien/index.php';
    }

    // Hiển thị form thêm sinh viên
    public function create()
    {
        $nganhs = $this->nganhHocModel->getAll();
        include __DIR__ . '/../views/sinhvien/create.php';
    }

    // Xử lý thêm sinh viên
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('controllers/SinhVienController.php?action=create');
        }

        // Lấy dữ liệu từ form
        $maSV = cleanInput($_POST['maSV'] ?? '');
        $hoTen = cleanInput($_POST['hoTen'] ?? '');
        $gioiTinh = cleanInput($_POST['gioiTinh'] ?? '');
        $ngaySinh = cleanInput($_POST['ngaySinh'] ?? '');
        $password = cleanInput($_POST['password'] ?? '');
        $maNganh = cleanInput($_POST['maNganh'] ?? '');

        // Validate
        $errors = [];

        if (empty($maSV)) {
            $errors[] = "Mã sinh viên không được để trống";
        } elseif ($this->sinhVienModel->checkExists($maSV)) {
            $errors[] = "Mã sinh viên đã tồn tại";
        }

        if (empty($hoTen)) $errors[] = "Họ tên không được để trống";
        if (empty($gioiTinh)) $errors[] = "Giới tính không được để trống";
        if (empty($ngaySinh)) $errors[] = "Ngày sinh không được để trống";
        if (empty($password)) $errors[] = "Mật khẩu không được để trống";
        if (empty($maNganh)) $errors[] = "Ngành học không được để trống";

        // Upload hình
        $hinh = '';
        if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === UPLOAD_ERR_OK) {
            $hinh = uploadImage($_FILES['hinh']);
            if (!$hinh) {
                $errors[] = "Upload hình không thành công";
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            redirect('controllers/SinhVienController.php?action=create');
        }

        // Thêm sinh viên
        $this->sinhVienModel->MaSV = $maSV;
        $this->sinhVienModel->HoTen = $hoTen;
        $this->sinhVienModel->GioiTinh = $gioiTinh;
        $this->sinhVienModel->NgaySinh = $ngaySinh;
        $this->sinhVienModel->Hinh = $hinh;
        $this->sinhVienModel->Password = $password; // Trong thực tế nên hash password
        $this->sinhVienModel->MaNganh = $maNganh;

        if ($this->sinhVienModel->create()) {
            redirect('controllers/SinhVienController.php?action=index', 'Thêm sinh viên thành công!', 'success');
        } else {
            redirect('controllers/SinhVienController.php?action=create', 'Có lỗi xảy ra khi thêm sinh viên!', 'error');
        }
    }

    // Hiển thị form sửa sinh viên
    public function edit()
    {
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            redirect('controllers/SinhVienController.php?action=index');
        }

        $sinhVien = $this->sinhVienModel->getById($id);
        if (!$sinhVien) {
            redirect('controllers/SinhVienController.php?action=index', 'Không tìm thấy sinh viên!', 'error');
        }

        $nganhs = $this->nganhHocModel->getAll();
        include __DIR__ . '/../views/sinhvien/edit.php';
    }

    // Xử lý cập nhật sinh viên
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('controllers/SinhVienController.php?action=index');
        }

        $maSV = cleanInput($_POST['maSV'] ?? '');
        $hoTen = cleanInput($_POST['hoTen'] ?? '');
        $gioiTinh = cleanInput($_POST['gioiTinh'] ?? '');
        $ngaySinh = cleanInput($_POST['ngaySinh'] ?? '');
        $password = cleanInput($_POST['password'] ?? '');
        $maNganh = cleanInput($_POST['maNganh'] ?? '');
        $oldHinh = cleanInput($_POST['oldHinh'] ?? '');

        // Validate
        $errors = [];
        if (empty($hoTen)) $errors[] = "Họ tên không được để trống";
        if (empty($gioiTinh)) $errors[] = "Giới tính không được để trống";
        if (empty($ngaySinh)) $errors[] = "Ngày sinh không được để trống";
        if (empty($password)) $errors[] = "Mật khẩu không được để trống";
        if (empty($maNganh)) $errors[] = "Ngành học không được để trống";

        // Upload hình mới (nếu có)
        $hinh = $oldHinh;
        if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === UPLOAD_ERR_OK) {
            $newHinh = uploadImage($_FILES['hinh']);
            if ($newHinh) {
                $hinh = $newHinh;
                // Xóa hình cũ nếu cần
                if (!empty($oldHinh) && file_exists('.' . $oldHinh)) {
                    unlink('.' . $oldHinh);
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            redirect('controllers/SinhVienController.php?action=edit&id=' . $maSV);
        }

        // Cập nhật sinh viên
        $this->sinhVienModel->MaSV = $maSV;
        $this->sinhVienModel->HoTen = $hoTen;
        $this->sinhVienModel->GioiTinh = $gioiTinh;
        $this->sinhVienModel->NgaySinh = $ngaySinh;
        $this->sinhVienModel->Hinh = $hinh;
        $this->sinhVienModel->Password = $password;
        $this->sinhVienModel->MaNganh = $maNganh;

        if ($this->sinhVienModel->update()) {
            redirect('controllers/SinhVienController.php?action=index', 'Cập nhật sinh viên thành công!', 'success');
        } else {
            redirect('controllers/SinhVienController.php?action=edit&id=' . $maSV, 'Có lỗi xảy ra khi cập nhật!', 'error');
        }
    }

    // Hiển thị chi tiết sinh viên
    public function detail()
    {
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            redirect('controllers/SinhVienController.php?action=index');
        }

        $sinhVien = $this->sinhVienModel->getById($id);
        if (!$sinhVien) {
            redirect('controllers/SinhVienController.php?action=index', 'Không tìm thấy sinh viên!', 'error');
        }

        include __DIR__ . '/../views/sinhvien/detail.php';
    }

    // Hiển thị xác nhận xóa
    public function delete()
    {
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            redirect('controllers/SinhVienController.php?action=index');
        }

        $sinhVien = $this->sinhVienModel->getById($id);
        if (!$sinhVien) {
            redirect('controllers/SinhVienController.php?action=index', 'Không tìm thấy sinh viên!', 'error');
        }

        include __DIR__ . '/../views/sinhvien/delete.php';
    }

    // Xử lý xóa sinh viên
    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('controllers/SinhVienController.php?action=index');
        }

        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            redirect('controllers/SinhVienController.php?action=index');
        }

        if ($this->sinhVienModel->delete($id)) {
            redirect('controllers/SinhVienController.php?action=index', 'Xóa sinh viên thành công!', 'success');
        } else {
            redirect('controllers/SinhVienController.php?action=index', 'Có lỗi xảy ra khi xóa sinh viên!', 'error');
        }
    }
}

// Xử lý request
$action = $_GET['action'] ?? 'index';
$controller = new SinhVienController();

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'update':
        $controller->update();
        break;
    case 'detail':
        $controller->detail();
        break;
    case 'delete':
        $controller->delete();
        break;
    case 'destroy':
        $controller->destroy();
        break;
    default:
        $controller->index();
        break;
}
