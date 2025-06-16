<?php
require_once __DIR__ . '/../config/database.php';

class HocPhan
{
    private $conn;
    private $table = "HocPhan";

    public $MaHP;
    public $TenHP;
    public $SoTinChi;
    public $SoLuongDuKien;
    public $SoLuongDaDangKy;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả học phần
    public function getAll()
    {
        $query = "SELECT *, (SoLuongDuKien - SoLuongDaDangKy) as SoLuongConLai 
                  FROM " . $this->table . " 
                  ORDER BY MaHP";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Lấy học phần theo mã
    public function getById($id)
    {
        $query = "SELECT *, (SoLuongDuKien - SoLuongDaDangKy) as SoLuongConLai 
                  FROM " . $this->table . " 
                  WHERE MaHP = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Lấy nhiều học phần theo danh sách mã
    public function getByIds($ids)
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $query = "SELECT *, (SoLuongDuKien - SoLuongDaDangKy) as SoLuongConLai 
                  FROM " . $this->table . " 
                  WHERE MaHP IN ($placeholders)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($ids);

        return $stmt->fetchAll();
    }

    // Cập nhật số lượng đã đăng ký
    public function updateSoLuongDaDangKy($maHP, $soLuong)
    {
        $query = "UPDATE " . $this->table . " 
                  SET SoLuongDaDangKy = SoLuongDaDangKy + :soLuong 
                  WHERE MaHP = :maHP";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':soLuong', $soLuong, PDO::PARAM_INT);
        $stmt->bindParam(':maHP', $maHP);

        return $stmt->execute();
    }

    // Kiểm tra còn slot không
    public function checkAvailable($maHP)
    {
        $hocPhan = $this->getById($maHP);
        if ($hocPhan) {
            return $hocPhan['SoLuongConLai'] > 0;
        }
        return false;
    }
}
