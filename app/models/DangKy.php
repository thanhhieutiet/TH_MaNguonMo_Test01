<?php
require_once __DIR__ . '/../config/database.php';

class DangKy
{
    private $conn;
    private $table = "DangKy";
    private $detailTable = "ChiTietDangKy";

    public $MaDK;
    public $NgayDK;
    public $MaSV;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Tạo đăng ký mới
    public function create($maSV, $danhSachHP)
    {
        try {
            $this->conn->beginTransaction();

            // Tạo bản ghi đăng ký
            $query = "INSERT INTO " . $this->table . " (NgayDK, MaSV) VALUES (CURDATE(), :maSV)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maSV', $maSV);
            $stmt->execute();

            $maDK = $this->conn->lastInsertId();

            // Thêm chi tiết đăng ký
            $queryDetail = "INSERT INTO " . $this->detailTable . " (MaDK, MaHP) VALUES (:maDK, :maHP)";
            $stmtDetail = $this->conn->prepare($queryDetail);

            foreach ($danhSachHP as $maHP) {
                $stmtDetail->bindParam(':maDK', $maDK);
                $stmtDetail->bindParam(':maHP', $maHP);
                $stmtDetail->execute();
            }

            $this->conn->commit();
            return $maDK;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    // Lấy danh sách đăng ký của sinh viên
    public function getByStudent($maSV)
    {
        $query = "SELECT dk.*, 
                         GROUP_CONCAT(hp.TenHP SEPARATOR ', ') as DanhSachHP,
                         SUM(hp.SoTinChi) as TongTinChi
                  FROM " . $this->table . " dk
                  JOIN " . $this->detailTable . " ct ON dk.MaDK = ct.MaDK
                  JOIN HocPhan hp ON ct.MaHP = hp.MaHP
                  WHERE dk.MaSV = :maSV
                  GROUP BY dk.MaDK
                  ORDER BY dk.NgayDK DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maSV', $maSV);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Lấy chi tiết đăng ký
    public function getDetailById($maDK)
    {
        $query = "SELECT dk.*, sv.HoTen,
                         hp.MaHP, hp.TenHP, hp.SoTinChi
                  FROM " . $this->table . " dk
                  JOIN SinhVien sv ON dk.MaSV = sv.MaSV
                  JOIN " . $this->detailTable . " ct ON dk.MaDK = ct.MaDK
                  JOIN HocPhan hp ON ct.MaHP = hp.MaHP
                  WHERE dk.MaDK = :maDK";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maDK', $maDK);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Xóa đăng ký
    public function delete($maDK)
    {
        try {
            $this->conn->beginTransaction();

            // Xóa chi tiết trước
            $queryDetail = "DELETE FROM " . $this->detailTable . " WHERE MaDK = :maDK";
            $stmtDetail = $this->conn->prepare($queryDetail);
            $stmtDetail->bindParam(':maDK', $maDK);
            $stmtDetail->execute();

            // Xóa đăng ký
            $query = "DELETE FROM " . $this->table . " WHERE MaDK = :maDK";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDK', $maDK);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    // Kiểm tra sinh viên đã đăng ký học phần chưa
    public function checkStudentRegistered($maSV, $maHP)
    {
        $query = "SELECT COUNT(*) as count 
                  FROM " . $this->table . " dk
                  JOIN " . $this->detailTable . " ct ON dk.MaDK = ct.MaDK
                  WHERE dk.MaSV = :maSV AND ct.MaHP = :maHP";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maSV', $maSV);
        $stmt->bindParam(':maHP', $maHP);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}
