<?php
require_once __DIR__ . '/../config/database.php';

class NganhHoc
{
    private $conn;
    private $table = "NganhHoc";

    public $MaNganh;
    public $TenNganh;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả ngành học
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY MaNganh";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Lấy ngành học theo mã
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE MaNganh = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }
}
