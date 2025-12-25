<?php
class voucher extends Controller
{
    public function show()
    {
        $obj = $this->model("Advoucher");
        $voucherList = $obj->getAll();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $vc_id = $_POST["vc_id"] ?? "";
            $ngaybatdau = $_POST["ngaybatdau"] ?? "";
            $ngayketthuc = $_POST["ngayketthuc"] ?? "";
            $giatoithieu = $_POST["giatoithieu"] ?? "";
            $giagiam = $_POST["giagiam"] ?? "";
            $soluong = $_POST["soluong"] ?? "";
            $trangthai = $_POST["trangthai"] ?? "1";

            if (!empty($vc_id)) {
                // Kiểm tra voucher có tồn tại không
                $existingVoucher = $obj->getById($vc_id);
                
                if ($existingVoucher) {
                    // Cập nhật voucher
                    $obj->update($vc_id, $ngaybatdau, $ngayketthuc, $giatoithieu, $giagiam, $soluong, $trangthai);
                } else {
                    // Thêm voucher mới
                    $obj->insert($vc_id, $ngaybatdau, $ngayketthuc, $giatoithieu, $giagiam, $soluong, $trangthai);
                }
            }

            header("Location: " . APP_URL . "/voucher/show");
            exit();
        }

        $this->view("adminPage", [
            "page" => "VoucherView",
            "voucherList" => $voucherList
        ]);
    }

    public function edit($vc_id)
    {
        $obj = $this->model("Advoucher");
        $voucher = $obj->getById($vc_id);

        $this->view("adminPage", [
            "page" => "VoucherView",
            "voucherList" => $obj->getAll(),
            "editVoucher" => $voucher
        ]);
    }

    public function delete($vc_id)
    {
        $obj = $this->model("Advoucher");
        $obj->deleteVoucher($vc_id);
        header("Location: " . APP_URL . "/voucher/show");
    }
}
?>
