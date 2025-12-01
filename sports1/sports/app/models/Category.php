<?php
require_once __DIR__ . '/BaseModel.php';

class Category extends BaseModel
{
    public function getAll(): array
    {
        $sql = "SELECT * FROM category ORDER BY name";
        return $this->db->query($sql)->fetchAll();
    }
    public function create(array $data): int
    {
        $sql = "INSERT INTO category (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => $data['name']]);
        return (int)$this->db->lastInsertId();
    }
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM category WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE category SET name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':id'   => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM category WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }


}
