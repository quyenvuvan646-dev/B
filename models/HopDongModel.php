<?php
require_once 'BaseModel.php';

class HopDongModel extends BaseModel
{
    protected $table = 'hop_dong';

    // Get contract by user email
    public function getContractByEmail($email)
    {
        $sql = "SELECT * FROM $this->table WHERE user_email = ?";
        $result = $this->select($sql, [$email]);
        return $result ? $result[0] : null;
    }

    // Create new contract registration
    public function createContract($email, $fullName, $phone, $companyName, $businessAddress, $taxId = null, $businessLicense = null)
    {
        $sql = "INSERT INTO $this->table (user_email, full_name, phone, company_name, business_address, tax_id, business_license, terms_accepted, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 1, 'pending')";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$email, $fullName, $phone, $companyName, $businessAddress, $taxId, $businessLicense]);
    }

    // Update contract with file path
    public function updateContractFile($email, $filePath)
    {
        $sql = "UPDATE $this->table SET contract_file = ? WHERE user_email = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$filePath, $email]);
    }

    // Get all pending contracts (for admin)
    public function getPendingContracts()
    {
        $sql = "SELECT * FROM $this->table WHERE status = 'pending' ORDER BY created_at ASC";
        return $this->select($sql, []);
    }

    // Update contract status (admin approval/rejection)
    public function updateContractStatus($email, $status, $adminNotes = null)
    {
        $sql = "UPDATE $this->table SET status = ?, admin_notes = ? WHERE user_email = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $adminNotes, $email]);
    }

    // Get contract by ID
    public function getContractById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = ?";
        $result = $this->select($sql, [$id]);
        return $result ? $result[0] : null;
    }

    // Check if user has existing pending/approved contract
    public function hasActiveContract($email)
    {
        $sql = "SELECT COUNT(*) as count FROM $this->table WHERE user_email = ? AND status IN ('pending', 'approved')";
        $result = $this->select($sql, [$email]);
        return $result && $result[0]['count'] > 0;
    }

    // Get contracts by status (for admin history)
    public function getContractsByStatus($status)
    {
        $sql = "SELECT * FROM $this->table WHERE status = ? ORDER BY created_at DESC";
        return $this->select($sql, [$status]);
    }
}

