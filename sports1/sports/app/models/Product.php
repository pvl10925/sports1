<?php
// Đảm bảo nạp đúng BaseModel
require_once __DIR__ . '/BaseModel.php'; 

class Product extends BaseModel
{
    /**
     * Lấy các số liệu thống kê cơ bản về Sản phẩm cho Admin Dashboard (getStats)
     * Đây là hàm khắc phục lỗi "Call to undefined method Product::getStats()"
     */
    public function getStats(): array
    {
        $sql = "SELECT 
                    COUNT(id) AS total_products,
                    SUM(number_sold) AS total_sold,
                    SUM(number_in_stock) AS total_stock
                FROM product";
        
        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * LẤY SẢN PHẨM CÓ LỌC (Dùng cho trang Cửa hàng/Tìm kiếm)
     */
    public function getFiltered(int $categoryId, int $brandId, string $priceRange, string $keyword): array
    {
        $sql = "SELECT p.*, b.name AS brand_name, c.name AS category_name 
                FROM product p
                JOIN brand b ON p.brand_id = b.id
                JOIN category c ON p.category_id = c.id
                WHERE 1=1"; // Điều kiện ban đầu luôn đúng

        $params = [];

        // 1. Lọc theo Danh mục
        if ($categoryId > 0) {
            $sql .= " AND p.category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }

        // 2. Lọc theo Thương hiệu
        if ($brandId > 0) {
            $sql .= " AND p.brand_id = :brandId";
            $params[':brandId'] = $brandId;
        }

        // 3. Lọc theo Từ khóa tìm kiếm
        if (!empty($keyword)) {
            $sql .= " AND (p.title LIKE :keyword OR p.description LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        // 4. Lọc theo Khoảng giá
        switch ($priceRange) {
            case '1':
            case '0-500':
                $sql .= " AND p.price < 500000";
                break;
            case '2':
            case '500-1000':
                $sql .= " AND p.price BETWEEN 500000 AND 1500000";
                break;
            case '3':
            case '1000-max':
                $sql .= " AND p.price > 1500000";
                break;
            case 'all':
            default:
                // Không thêm điều kiện giá
                break;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy tất cả sản phẩm
     */
    public function getAll(): array
    {
        $sql = "SELECT p.*, b.name AS brand_name, c.name AS category_name 
                FROM product p
                JOIN brand b ON p.brand_id = b.id
                JOIN category c ON p.category_id = c.id
                ORDER BY p.created_at DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Tìm sản phẩm theo ID
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT p.*, b.name AS brand_name, c.name AS category_name 
                FROM product p
                JOIN brand b ON p.brand_id = b.id
                JOIN category c ON p.category_id = c.id
                WHERE p.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Lấy các sản phẩm nổi bật/mới nhất
     */
    public function getFeatured(int $limit = 6): array
    {
        $sql = "SELECT * FROM product ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy các sản phẩm bán chạy nhất
     */
    public function getBestSelling(int $limit = 4): array
    {
        $sql = "SELECT * FROM product ORDER BY number_sold DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo sản phẩm mới
     * @param array $data keys: brand_id, category_id, title, price, stock, date_publication, description, image
     * @return int|false inserted id or false on failure
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO product (brand_id, category_id, title, price, number_in_stock, date_publication, description, image)
                VALUES (:brand_id, :category_id, :title, :price, :stock, :date_publication, :description, :image)";

        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
            ':brand_id' => $data['brand_id'] ?? null,
            ':category_id' => $data['category_id'] ?? null,
            ':title' => $data['title'] ?? null,
            ':price' => $data['price'] ?? 0,
            ':stock' => $data['stock'] ?? 0,
            ':date_publication' => $data['date_publication'] ?? null,
            ':description' => $data['description'] ?? null,
            ':image' => $data['image'] ?? null,
        ]);

        if ($ok) {
            return (int) $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Cập nhật sản phẩm theo id
     * @param int $id
     * @param array $data same keys as create
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE product SET
                    brand_id = :brand_id,
                    category_id = :category_id,
                    title = :title,
                    price = :price,
                    number_in_stock = :stock,
                    date_publication = :date_publication,
                    description = :description,
                    image = :image,
                    updated_at = NOW()
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':brand_id' => $data['brand_id'] ?? null,
            ':category_id' => $data['category_id'] ?? null,
            ':title' => $data['title'] ?? null,
            ':price' => $data['price'] ?? 0,
            ':stock' => $data['stock'] ?? 0,
            ':date_publication' => $data['date_publication'] ?? null,
            ':description' => $data['description'] ?? null,
            ':image' => $data['image'] ?? null,
            ':id' => $id,
        ]);
    }

    /**
     * Xóa sản phẩm theo id
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM product WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}