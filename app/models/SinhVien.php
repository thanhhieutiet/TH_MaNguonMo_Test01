<?php
require_once __DIR__ . '/../config/database.php';

class SinhVien
{
    private $conn;
    private $table = "SinhVien";

    public $MaSV;
    public $HoTen;
    public $GioiTinh;
    public $NgaySinh;
    public $Hinh;
    public $Password;
    public $MaNganh;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả sinh viên với phân trang
    public function getAll($page = 1, $limit = 4)
    {
        $offset = ($page - 1) * $limit;

        $query = "SELECT sv.*, ng.TenNganh 
                  FROM " . $this->table . " sv 
                  LEFT JOIN NganhHoc ng ON sv.MaNganh = ng.MaNganh 
                  ORDER BY sv.MaSV 
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Đếm tổng số sinh viên
    public function countAll()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result['total'];
    }

    // Lấy sinh viên theo mã
    public function getById($id)
    {
        $query = "SELECT sv.*, ng.TenNganh 
                  FROM " . $this->table . " sv 
                  LEFT JOIN NganhHoc ng ON sv.MaNganh = ng.MaNganh 
                  WHERE sv.MaSV = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Thêm sinh viên mới
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                  (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, Password, MaNganh) 
                  VALUES (:MaSV, :HoTen, :GioiTinh, :NgaySinh, :Hinh, :Password, :MaNganh)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':MaSV', $this->MaSV);
        $stmt->bindParam(':HoTen', $this->HoTen);
        $stmt->bindParam(':GioiTinh', $this->GioiTinh);
        $stmt->bindParam(':NgaySinh', $this->NgaySinh);
        $stmt->bindParam(':Hinh', $this->Hinh);
        $stmt->bindParam(':Password', $this->Password);
        $stmt->bindParam(':MaNganh', $this->MaNganh);

        return $stmt->execute();
    }

    // Cập nhật sinh viên
    public function update()
    {
        $query = "UPDATE " . $this->table . " 
                  SET HoTen = :HoTen, GioiTinh = :GioiTinh, NgaySinh = :NgaySinh, 
                      Hinh = :Hinh, Password = :Password, MaNganh = :MaNganh 
                  WHERE MaSV = :MaSV";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':MaSV', $this->MaSV);
        $stmt->bindParam(':HoTen', $this->HoTen);
        $stmt->bindParam(':GioiTinh', $this->GioiTinh);
        $stmt->bindParam(':NgaySinh', $this->NgaySinh);
        $stmt->bindParam(':Hinh', $this->Hinh);
        $stmt->bindParam(':Password', $this->Password);
        $stmt->bindParam(':MaNganh', $this->MaNganh);

        return $stmt->execute();
    }

    // Xóa sinh viên
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE MaSV = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Kiểm tra đăng nhập
    public function login($maSV, $password)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE MaSV = :maSV AND Password = :password";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maSV', $maSV);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Kiểm tra mã SV đã tồn tại
    public function checkExists($maSV)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE MaSV = :maSV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maSV', $maSV);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    function uploadImage($file)
    {
        $targetDir = 'public/uploads/sinhvien/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // tạo thư mục nếu chưa có
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $targetPath; // ✅ trả về đường dẫn đầy đủ
        }
        return false;
    }
}
