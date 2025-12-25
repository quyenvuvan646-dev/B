<?php
class khuyenmai extends Controller
{
    public function show()
    {
        $obj = $this->model("AdKhuyenMai");
        $obj2 = $this->model("AdProducModel");
        $products = $obj2->all("tblsanpham");
        $dataList = $obj->getAllWithProduct(); // << JOIN sản phẩm
        $dataView = $obj->getView();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $km_id = $_POST["km_id"];
            $maLoaiSP = $_POST["maLoaiSP"];
            $masp = $_POST["masp"];
            $phantram = $_POST["phantram"];
            $ngaybatdau = $_POST["ngaybatdau"];
            $ngayketthuc = $_POST["ngayketthuc"];

            $obj->insert($maLoaiSP, $masp, $phantram, $ngaybatdau, $ngayketthuc);
            header("Location: " . APP_URL . "/khuyenmai/show");
            exit();
        }

        $this->view("adminPage", [
            "page" => "khuyenmaiView",
            "products" => $products,
            "productList" => $dataList,
            "dataView" => $dataView
        ]);
    }

    public function delete($km_id)
    {
        $obj = $this->model("AdKhuyenMai");
        $obj->deleteKm($km_id);
        header("Location: " . APP_URL . "/khuyenmai/show");
    }

    public function edit($km_id = null)
    {
        if (!$km_id) {
            header("Location: " . APP_URL . "/khuyenmai/show");
            exit();
        }

        $obj = $this->model("AdKhuyenMai");
        $obj2 = $this->model("AdProducModel");
        $products = $obj2->all("tblsanpham");
        $dataView = $obj->getView();

        // Get promotion details
        $promotion = $obj->getById($km_id);
        if (!$promotion) {
            header("Location: " . APP_URL . "/khuyenmai/show");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $maLoaiSP = $_POST["maLoaiSP"];
            $masp = $_POST["masp"];
            $phantram = $_POST["phantram"];
            $ngaybatdau = $_POST["ngaybatdau"];
            $ngayketthuc = $_POST["ngayketthuc"];

            $obj->updateKm($km_id, $maLoaiSP, $masp, $phantram, $ngaybatdau, $ngayketthuc);
            header("Location: " . APP_URL . "/khuyenmai/show");
            exit();
        }

        $this->view("adminPage", [
            "page" => "khuyenmaiEditView",
            "products" => $products,
            "dataView" => $dataView,
            "promotion" => $promotion
        ]);
    }
    public function add()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $maLoaiSP = $_POST["maLoaiSP"] ?? "";
            $masp = $_POST["masp"] ?? "";
            $phantram = $_POST["phantram"];
            $ngaybatdau = $_POST["ngaybatdau"];
            $ngayketthuc = $_POST["ngayketthuc"];

            $obj = $this->model("AdKhuyenMai");

            if (empty($masp)) {
                // Nếu không chọn sản phẩm cụ thể => áp dụng cho cả loại
                $obj->insertForCategory($maLoaiSP, $phantram, $ngaybatdau, $ngayketthuc);
            } else {
                // Ngược lại => áp dụng cho 1 sản phẩm
                $obj->insert($maLoaiSP, $masp, $phantram, $ngaybatdau, $ngayketthuc);
            }
        }

        header("Location: " . APP_URL . "/khuyenmai/show");
    }
}
