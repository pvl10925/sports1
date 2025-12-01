<?php
// Dòng này nạp file chứa class kết nối cơ sở dữ liệu (Database::getConnection())
require_once __DIR__ . '/../config/db.php';

class BaseModel
{
    // Thuộc tính để lưu trữ kết nối PDO (là đối tượng cơ sở dữ liệu)
    protected PDO $db;

    // Phương thức khởi tạo (Constructor)
    public function __construct()
    {
        // Khởi tạo kết nối CSDL khi một Model được tạo ra
        $this->db = Database::getConnection();
    }

    // Các phương thức chung khác (nếu có, nhưng cơ bản chỉ cần kết nối này)
}