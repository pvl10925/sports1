<?php
require_once __DIR__ . '/BaseModel.php';

class Order extends BaseModel
{
    /** Thống kê tổng quan đơn hàng */
    public function getSummary(): array
    {
        $sql = "SELECT 
                    COUNT(*) AS total_orders,
                    COALESCE(SUM(total_cost),0) AS total_revenue,
                    SUM(CASE WHEN status = 'COMPLETED' THEN 1 ELSE 0 END) AS completed,
                    SUM(CASE WHEN status = 'PENDING'   THEN 1 ELSE 0 END) AS pending,
                    SUM(CASE WHEN status = 'CANCELLED' THEN 1 ELSE 0 END) AS cancelled
                FROM orders";
        $row = $this->db->query($sql)->fetch();
        if (!$row) {
            $row = [
                'total_orders'  => 0,
                'total_revenue' => 0,
                'completed'     => 0,
                'pending'       => 0,
                'cancelled'     => 0,
            ];
        }
        return $row;
    }

    /** Lấy X đơn hàng mới nhất để show bảng ở Dashboard */
    public function getRecent(int $limit = 5): array
    {
        $sql = "SELECT * FROM orders ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':id'     => $id,
        ]);
    }

    /** Lấy doanh thu theo tháng (6 tháng gần nhất) */
    public function getMonthlyRevenue(): array
    {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%m/%Y') AS month,
                    COALESCE(SUM(total_cost), 0) AS revenue
                FROM orders
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY created_at ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /** Lấy tốp 5 sản phẩm bán chạy nhất */
    public function getTopProducts(int $limit = 5): array
    {
        $sql = "SELECT 
                    p.title AS name,
                    SUM(od.number) AS quantity
                FROM order_detail od
                JOIN product p ON od.product_id = p.id
                GROUP BY od.product_id, p.title
                ORDER BY quantity DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /** Tạo đơn hàng mới */
    public function create(int $userId, float $totalCost, string $customerName, string $address, string $phone, array $cartItems): ?int
    {
        try {
            $this->db->beginTransaction();

            // Tạo đơn hàng
            $sql = "INSERT INTO orders (user_id, total_cost, customer_name, address, phone, status) 
                    VALUES (:user_id, :total_cost, :customer_name, :address, :phone, 'PENDING')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':total_cost' => $totalCost,
                ':customer_name' => $customerName,
                ':address' => $address,
                ':phone' => $phone,
            ]);

            $orderId = (int) $this->db->lastInsertId();

            // Tạo chi tiết đơn hàng
            $sqlDetail = "INSERT INTO order_detail (order_id, product_id, number, total_cost) 
                          VALUES (:order_id, :product_id, :number, :total_cost)";
            $stmtDetail = $this->db->prepare($sqlDetail);

            foreach ($cartItems as $item) {
                $productId = (int) $item['id'];
                $quantity = (int) $item['quantity'];
                $price = (float) $item['price'];
                $itemTotal = $price * $quantity;

                $stmtDetail->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $productId,
                    ':number' => $quantity,
                    ':total_cost' => $itemTotal,
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            return null;
        }
    }
}
