<?php
class RoleModel extends DB {
    private $table = 'roles';

    public function allRoles() {
        try {
            $query = "SELECT * FROM {$this->table} ORDER BY id ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rows) return $rows;
        } catch (Exception $e) {
            // Table may not exist — fall back to default mapping
        }

        // Fallback roles mapping when `roles` table is not present
        return [
            ['id' => 1, 'slug' => 'buyer', 'name' => 'Buyer', 'description' => 'Default buyer'],
            ['id' => 2, 'slug' => 'shipper', 'name' => 'Shipper', 'description' => 'Delivery personnel'],
            ['id' => 4, 'slug' => 'distributor', 'name' => 'Distributor', 'description' => 'Distributor'],
            ['id' => 5, 'slug' => 'admin', 'name' => 'Admin', 'description' => 'Administrator']
        ];
    }

    public function findById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findBySlug($slug) {
        $query = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $slug, $description = null) {
        $query = "INSERT INTO {$this->table} (name, slug, description) VALUES (:name, :slug, :desc)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':desc', $description);
        return $stmt->execute();
    }
}
