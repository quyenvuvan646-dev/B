<?php
class UserModel extends DB {

    private $table = "tbluser";

    public $email;
    public $password;
    public $fullname;
    public $token;
    public $user_role = 1; // Default buyer

    // ==============================
    // CREATE USER
    // ==============================
    public function create() {
        $query = "INSERT INTO {$this->table} 
            (fullname, email, password, verification_token, user_role) 
            VALUES (:fullname, :email, :password, :token, :user_role)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":fullname", $this->fullname);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":token", $this->token);
        $stmt->bindParam(":user_role", $this->user_role);

        return $stmt->execute();
    }

    // ==============================
    // VERIFY EMAIL
    // ==============================
    public function verify($token) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE verification_token = :token AND is_verified = 0";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt;
    }

    public function setVerified($token) {
        $query = "UPDATE {$this->table} 
                  SET is_verified = 1, verification_token = NULL 
                  WHERE verification_token = :token";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":token", $token);
        return $stmt->execute();
    }

    // ==============================
    // EMAIL CHECK
    // ==============================
    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt;
    }

    // ==============================
    // UPDATE PASSWORD
    // ==============================
    public function updatePassword($email, $newPasswordHash) {
        $query = "UPDATE {$this->table} SET password = :password WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":password", $newPasswordHash);
        $stmt->bindParam(":email", $email);
        return $stmt->execute();
    }

    // ==============================
    // ADMIN FUNCTIONS
    // ==============================
    public function allUsers() {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setLock($email, $locked = true) {
        $query = "UPDATE {$this->table} SET is_locked = :locked WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $val = $locked ? 1 : 0;
        $stmt->bindParam(":locked", $val);
        $stmt->bindParam(":email", $email);
        return $stmt->execute();
    }

    public function deleteUser($email) {
        $query = "DELETE FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        return $stmt->execute();
    }

    // ==============================
    // ORDER STATS
    // ==============================
    public function getUserOrderStats($email) {
        $sql = "SELECT COUNT(*) as order_count, 
                       COALESCE(SUM(total_amount),0) as total_revenue 
                FROM orders 
                WHERE user_email = ? 
                AND transaction_info IN ('thanhtoanvnpay','chothanhtoan')";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================
    // ROLE FUNCTIONS (GỘP VÀO USER)
    // ==============================

    private $roleMap = [
        1 => ['id'=>1,'slug'=>'buyer','name'=>'Buyer'],
        2 => ['id'=>2,'slug'=>'shipper','name'=>'Shipper'],
        3 => ['id'=>3,'slug'=>'seller','name'=>'Seller'],
        4 => ['id'=>4,'slug'=>'distributor','name'=>'Distributor'],
        5 => ['id'=>5,'slug'=>'admin','name'=>'Admin'],
    ];

    public function getUserRolesById($userId) {
        $sql = "SELECT user_role FROM {$this->table}
                WHERE user_id = :uid LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":uid", $userId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $roleId = $row ? $row['user_role'] : null;

        if (!$roleId) return [];
        return [$this->roleMap[$roleId] ?? ['id'=>$roleId,'slug'=>'role_'.$roleId,'name'=>'Role '.$roleId]];
    }

    public function assignRole($userId, $roleId) {
        $sql = "UPDATE {$this->table} SET user_role = :rid WHERE user_id = :uid";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":rid", $roleId);
        $stmt->bindParam(":uid", $userId);
        return $stmt->execute();
    }

    public function removeRole($userId, $roleId) {
        $default = 1;
        $sql = "UPDATE {$this->table} 
                SET user_role = :def 
                WHERE user_id = :uid AND user_role = :rid";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":def", $default);
        $stmt->bindParam(":uid", $userId);
        $stmt->bindParam(":rid", $roleId);
        return $stmt->execute();
    }

    public function syncRoles($userId, $roleIds = []) {
        $roleId = (is_array($roleIds) && count($roleIds) > 0) ? intval($roleIds[0]) : 1;
        return $this->assignRole($userId, $roleId);
    }

    public function hasRoleBySlug($userId, $slug) {
        $sql = "SELECT user_role FROM {$this->table}
                WHERE user_id = :uid LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":uid", $userId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $roleId = $row ? $row['user_role'] : null;

        if (!$roleId) return false;

        return isset($this->roleMap[$roleId]) 
            ? $this->roleMap[$roleId]['slug'] === $slug 
            : false;
    }

    // ==============================
    // UPDATE USER PROFILE
    // ==============================
    public function updateProfile($email, $fullname, $phone = null, $address = null) {
        // Cập nhật đầy đủ fullname, phone, address (phone/address có thể null)
        $query = "UPDATE {$this->table} SET fullname = :fullname, phone = :phone, address = :address WHERE email = :email";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":fullname", $fullname);
        $stmt->bindParam(":email", $email);

        $phoneParam = ($phone === '' ? null : $phone);
        $addressParam = ($address === '' ? null : $address);
        $stmt->bindValue(":phone", $phoneParam, $phoneParam === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(":address", $addressParam, $addressParam === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        return $stmt->execute();
    }

}
