<?php
require_once __DIR__ . '/BaseModel.php'; 

class Account extends BaseModel
{
    // Hàm tìm tài khoản bằng Email (cho chức năng Quên mật khẩu)
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT id, username, decryptedPassword, email FROM account WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    
    // Hàm tìm bằng Tên đăng nhập và Mật khẩu (cho chức năng Đăng nhập)
    public function findByUsernameAndPassword(string $username, string $password): ?array
    {
        $sql = "SELECT * FROM account WHERE username = :u AND password = :p LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':u' => $username,
            ':p' => $password, 
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // ... (Các hàm khác như getAll, find,...)

    // Kiểm tra username đã tồn tại hay chưa
    public function existsByUsername(string $username): bool
    {
        $sql = "SELECT id FROM account WHERE username = :u LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':u' => $username]);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra email đã tồn tại hay chưa
    public function existsByEmail(string $email): bool
    {
        $sql = "SELECT id FROM account WHERE email = :e LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':e' => $email]);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo account và profile user trong 1 transaction
    public function createAccountWithProfile(array $data)
    {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO account (username, password, decryptedPassword, email, role) VALUES (:u, :p, :dp, :e, :r)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':u' => $data['username'],
                ':p' => $data['password'],
                ':dp' => $data['password'],
                ':e' => $data['email'],
                ':r' => $data['role'] ?? 'USER',
            ]);

            $accountId = (int) $this->db->lastInsertId();

            $sql2 = "INSERT INTO user (account_id, name, address, phone) VALUES (:aid, :name, :address, :phone)";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([
                ':aid' => $accountId,
                ':name' => $data['name'] ?? '',
                ':address' => $data['address'] ?? null,
                ':phone' => $data['phone'] ?? null,
            ]);

            $this->db->commit();
            return $accountId;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /** Lấy user_id từ account_id */
    public function getUserIdByAccountId(int $accountId): ?int
    {
        $sql = "SELECT id FROM user WHERE account_id = :account_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':account_id' => $accountId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int) $row['id'] : null;
    }

    /** Lấy thông tin user từ user_id */
    public function getUserData(int $userId): ?array
    {
        $sql = "SELECT name, address, phone FROM user WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Tạo user cho guest (khách không đăng nhập) */
    public function createGuestUser(string $name, string $phone, string $address): ?int
    {
        try {
            $this->db->beginTransaction();

            // Tạo account guest
            $guestUsername = 'guest_' . time() . '_' . rand(1000, 9999);
            $guestEmail = 'guest_' . time() . '@temp.com';
            $guestPassword = md5(uniqid());

            $sql = "INSERT INTO account (username, password, decryptedPassword, email, role) 
                    VALUES (:u, :p, :dp, :e, 'USER')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':u' => $guestUsername,
                ':p' => $guestPassword,
                ':dp' => $guestPassword,
                ':e' => $guestEmail,
            ]);

            $accountId = (int) $this->db->lastInsertId();

            // Tạo user profile
            $sql2 = "INSERT INTO user (account_id, name, address, phone) 
                     VALUES (:aid, :name, :address, :phone)";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([
                ':aid' => $accountId,
                ':name' => $name,
                ':address' => $address,
                ':phone' => $phone,
            ]);

            $userId = (int) $this->db->lastInsertId();

            $this->db->commit();
            return $userId;
        } catch (Exception $e) {
            $this->db->rollBack();
            return null;
        }
    }
}