<?php
require_once __DIR__ . '/BaseModel.php';

class Brand extends BaseModel
{
    public function getAll(): array
    {
        $sql = "SELECT * FROM brand ORDER BY name";
        return $this->db->query($sql)->fetchAll();
    }
    public function create(array $data): int
    {
        $sql = "INSERT INTO brand (name, description) VALUES (:name, :description)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM brand WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE brand SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':id'          => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM brand WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }


}
