<?php
class Product extends Controller{
    public function show(){
        $obj=$this->model("AdProducModel");
        $data=$obj->all("tblsanpham");
        $this->view("adminPage",["page"=>"ProductListView","productList"=>$data]);
    }
    public function delete($id){
        $obj=$this->model("AdProducModel");
        $obj->delete("tblsanpham",$id);
        header("Location:".APP_URL."/Product/");    
        exit();
    }
    public function create(){
        $obj = $this->model("AdProducModel");
        $obj2 = $this->model("AdProductTypeModel");
        $producttype = $obj2->all("tblloaisp");
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $masp_goc = $_POST["txt_masp"];
            $masp = preg_replace('/\s+/', '', $masp_goc);
            $tensp = $_POST["txt_tensp"];
            $maloaisp = $_POST["txt_maloaisp"];
            $soluong = $_POST["txt_soluong"];
            $gianhap = $_POST["txt_gianhap"];
            $giaxuat = $_POST["txt_giaxuat"];
            $khuyenmai = $_POST["txt_khuyenmai"];
            $mota = $_POST["txt_mota"];
            $ngaytao = $_POST["create_date"];
            $emailSeller = $_POST["txt_email"] ?? ($_SESSION['user']['email'] ?? '');
            $hinhanh = "";
            if (!empty($_FILES["uploadfile"]["name"])) {
                $hinhanh = $_FILES["uploadfile"]["name"];
                $file_tmp = $_FILES["uploadfile"]["tmp_name"];
                move_uploaded_file($file_tmp, "./public/images/" . $hinhanh);
            }

            $obj->insert($maloaisp,$masp, $tensp, $hinhanh, $soluong, $gianhap, $giaxuat, $khuyenmai, $mota, $ngaytao, $emailSeller);
            header("Location: " . APP_URL . "/Product/");
            exit();
        }
        $this->view("adminPage", ["page" => "ProductView", "producttype" => $producttype]);
    }
   public function edit($masp){
        $obj = $this->model("AdProducModel");
        $obj2 = $this->model("AdProductTypeModel");
        $producttype = $obj2->all("tblloaisp");
        $product = $obj->find("tblsanpham", $masp);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $masp = $_POST["txt_masp"];
            $tensp = $_POST["txt_tensp"];
            $maloaisp = $_POST["txt_maloaisp"];
            $soluong = $_POST["txt_soluong"];
            $gianhap = $_POST["txt_gianhap"];
            $giaxuat = $_POST["txt_giaxuat"];
            $khuyenmai = $_POST["txt_khuyenmai"];
            $mota = $_POST["txt_mota"];
            $ngaytao = $_POST["create_date"];
            $emailSeller = $_POST["txt_email"] ?? ($_SESSION['user']['email'] ?? ($product['email'] ?? ''));
            $hinhanh = $product['hinhanh'];
            if (!empty($_FILES["uploadfile"]["name"])) {
                $hinhanh = $_FILES["uploadfile"]["name"];
                $file_tmp = $_FILES["uploadfile"]["tmp_name"];
                move_uploaded_file($file_tmp, "./public/images/" . $hinhanh);
            }
            $obj->update($maloaisp,$masp, $tensp,$hinhanh, $soluong, $gianhap, $giaxuat, $khuyenmai, $mota, $ngaytao, $emailSeller);
            header("Location: " . APP_URL . "/Product/");
            exit();
        }
        $this->view("adminPage", [
            "page" => "ProductView", //ProductView
            "producttype" => $producttype,
            "editItem" => $product
        ]);

    }
    
}
