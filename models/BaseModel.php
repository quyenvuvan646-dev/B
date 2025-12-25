<?php

require_once __DIR__ . '/../app/DB.php';

class BaseModel extends DB{
       // Danh sách bảng và cột khóa chính tương ứng
    protected    $primaryKeys = [
            'tblsanpham'    => 'masp',
            'tblloaisp'  => 'maLoaiSP'
            // thêm các bảng khác nếu cần
        ];
    public function all($table) {
        $sql = "SELECT * FROM $table";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();       
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public  function find($table, $id) {
        // Kiểm tra bảng có trong danh sách không
        if (!array_key_exists($table, $this->primaryKeys)) {
            throw new Exception("Bảng không hợp lệ hoặc chưa được định nghĩa.");
        }
        $column = $this->primaryKeys[$table];
        $sql = "SELECT * FROM $table WHERE $column = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //phương thức kiểm tra
    public function check($table, $column, $id) {
        $sql = "SELECT COUNT(*) FROM $table WHERE $column = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    // xóa bảng
    public  function delete($table,$id){
        if (!array_key_exists($table, $this->primaryKeys)) {
            throw new Exception("Bảng không hợp lệ hoặc chưa được định nghĩa.");
        }
        $column = $this->primaryKeys[$table];
        if($this->check($table, $column, $id)>0){
            $sql="DELETE FROM $table WHERE $column=:id"; 
            $stmt=$this->db->prepare($sql);
            $stmt->bindParam(":id",$id);
            return $stmt->execute();   
        }
        else{
            return false;
        }
        
    }   
    // Thực thi câu lệnh INSERT/UPDATE/DELETE
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Thực thi câu lệnh SELECT
    public function select($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy ID vừa insert
    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }
}