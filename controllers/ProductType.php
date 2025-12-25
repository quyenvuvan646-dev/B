<?php
class ProductType extends Controller{
    public function show(){
        $obj = $this->model("AdProductTypeModel");
        $data = $obj->all("tblloaisp");
        
        $this->view("adminPage",["page"=>"ProductTypeView","productList"=>$data]);
    }
    
    public function delete($id){
        $obj = $this->model("AdProductTypeModel");
        $obj->query("DELETE FROM tblsanpham WHERE maLoaiSP = :maLoaiSP", [':maLoaiSP' => $id]);
        $obj->delete("tblloaisp",$id);
        header("Location:".APP_URL."/ProductType/");    
        exit();
    }
    
    public function deleteProductType($id){
        $obj = $this->model("AdProductTypeModel");
        $obj->query("DELETE FROM tblsanpham WHERE maLoaiSP = :maLoaiSP", [':maLoaiSP' => $id]);
        $obj->delete("tblloaisp",$id);
        header("Location:".APP_URL."/ProductType/");    
        exit();
    }
    
    public function create(){
        $txt_maloaisp = isset($_POST["txt_maloaisp"]) ? trim($_POST["txt_maloaisp"]) : "";
        $txt_tenloaisp = isset($_POST["txt_tenloaisp"]) ? trim($_POST["txt_tenloaisp"]) : "";
        $txt_motaloaisp = isset($_POST["txt_motaloaisp"]) ? trim($_POST["txt_motaloaisp"]) : "";

        $obj = $this->model("AdProductTypeModel");

        if ($txt_maloaisp === "" || $txt_tenloaisp === "") {
            $_SESSION['pt_error'] = 'Vui lòng nhập đầy đủ Mã loại SP và Tên loại SP';
            header("Location:".APP_URL."/ProductType/");
            exit();
        }

        // Kiểm tra trùng mã
        $existing = $obj->find("tblloaisp", $txt_maloaisp);
        if ($existing) {
            $_SESSION['pt_error'] = 'Mã loại sản phẩm đã tồn tại. Vui lòng chọn mã khác';
            header("Location:".APP_URL."/ProductType/");
            exit();
        }

        // Thêm với email rỗng để tránh NOT NULL
        $obj->insert($txt_maloaisp, $txt_tenloaisp, $txt_motaloaisp, "");
        $saved = $obj->find("tblloaisp", $txt_maloaisp);
        if ($saved) {
            $_SESSION['pt_success'] = 'Thêm loại sản phẩm thành công';
        } else {
            $_SESSION['pt_error'] = 'Đã cố gắng thêm nhưng dữ liệu chưa lưu vào database. Vui lòng thử lại.';
        }
        header("Location:".APP_URL."/ProductType/");    
        exit();
    }
    
    public function edit($maLoaiSP)
    {
        $obj = $this->model("AdProductTypeModel");
        $product = $obj->find("tblloaisp", $maLoaiSP);
        $productList = $obj->all("tblloaisp");
        
        $this->view("adminPage",["page"=>"ProductTypeView",
                            'productList' => $productList,
                            'editItem' => $product]);
    }
    
    public function update($maLoaiSP)
    {
        $tenLoaiSP = trim($_POST['txt_tenloaisp'] ?? '');
        $moTaLoaiSP = trim($_POST['txt_motaloaisp'] ?? '');
        
        $obj = $this->model("AdProductTypeModel");
        $obj->update($maLoaiSP, $tenLoaiSP, $moTaLoaiSP);
        $_SESSION['pt_success'] = 'Cập nhật loại sản phẩm thành công';
        header("Location:".APP_URL."/ProductType/");    
        exit();
    }

}

