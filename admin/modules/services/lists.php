<?php

if(!defined('_INCODE'))
die('Access denied...');

$data = [
    'pageTitle' => 'Quản lý dịch vụ'
];

layout('header', 'admin', $data);
layout('breadcrumb', 'admin', $data);


$allService = getRaw("SELECT * FROM services");


// Xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody('get');
    

    // Xử lý lọc theo từ khóa
    if(!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        
        if(!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        }else {
            $operator = 'WHERE';
        }

        $filter .= " $operator tenphong LIKE '%$keyword%'";

    }

    //Xử lý lọc Status
    if(!empty($body['status'])) {
        $status = $body['status'];

        if($status == 2) {
            $statusSql = 0;
        } else {
            $statusSql = $status;
        }

        if(!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        }else {
            $operator = 'WHERE';
        }
        
        $filter .= "$operator trangthai=$statusSql";
    }
}

// Xử lý thêm người dùng
if(isPost()) {
    // Validate form
    $body = getBody(); // lấy tất cả dữ liệu trong form
    $errors = [];  // mảng lưu trữ các lỗi
    
    //Valide họ tên: Bắt buộc phải nhập, >=5 ký tự
    if(empty(trim($body['tendichvu']))) {
        $errors['tendichvu']['required'] = '** Bạn chưa nhập tên dịch vụ';
    }

    if(empty(trim($body['donvitinh']))) {
        $errors['donvitinh']['required'] = '** Bạn chưa chọn đơn vị tính';
    }

    if(empty(trim($body['giadichvu']))) {
        $errors['giadichvu']['required'] = '** Bạn chưa nhập giá dịch vụ';
    }
   
   // Kiểm tra mảng error
  if(empty($errors)) {
    // không có lỗi nào
    $dataInsert = [
        'tendichvu' => $body['tendichvu'],
        'donvitinh' => $body['donvitinh'],
        'giadichvu' => $body['giadichvu'],
    ];

    $insertStatus = insert('services', $dataInsert);
    if ($insertStatus) {
        setFlashData('msg', 'Thêm dịch vụ khách hàng thành công');
        setFlashData('msg_type', 'suc');
        redirect('admin/?module=services');
    }

  }else {
        // Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra chính xác thông tin nhập vào');
        setFlashData('msg_type', 'err');
        setFlashData('errors', $errors);
        setFlashData('old', $body);  // giữ lại các trường dữ liệu hợp lê khi nhập vào
    }

}

$msg =getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
?>

<?php
layout('navbar', 'admin', $data);
?>

<div class="container-fluid">

    <div id="MessageFlash">          
        <?php getMsg($msg, $msgType);?>          
    </div>
    <!-- Them -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h4 style="margin: 20px 0">Thêm dịch vụ mới</h4>
            <hr />
            <form action="" method="post" class="row" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="col-12">
                    <div class="form-group">
                        <label for="">Tên dịch vụ <span style="color: red">*</span></label>
                        <input type="text" placeholder="Tên dịch vụ" name="tendichvu" id="" class="form-control" value="<?php echo old('tendichvu', $old); ?>">
                        <?php echo form_error('tendichvu', $errors, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Đơn vị tính <span style="color: red">*</span></label>
                        <select name="donvitinh" id="" class="form-select">
                            <option value="">Chọn đơn vị</option>
                            <option value="KWh">KWh</option>
                            <option value="khoi">Khối</option>
                            <option value="nguoi">Người</option>
                            <option value="thang">Tháng</option>
                        </select>
                        <?php echo form_error('donvitinh', $errors, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Giá dịch vụ <span style="color: red">*</span></label>
                        <input type="text" placeholder="Giá dịch vụ" name="giadichvu" id="" class="form-control" value="<?php echo old('giadichvu', $old); ?>">
                        <?php echo form_error('giadichvu', $errors, '<span class="error">', '</span>'); ?>
                    </div>

                </div>
                <div class="form-group">                    
                    <div class="btn-row">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Thêm dịch vụ</button>
                        <a style="margin-left: 20px " href="<?php echo getLinkAdmin('services') ?>" class="btn btn-success"><i class="fa fa-forward"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sua -->
    <div id="myModalEdit" class="modal">
        <div class="modal-content">
            <span class="closeEdit">&times;</span>
            <h4 style="margin: 20px 0">Cập nhật dịch vụ</h4>
            <hr />
            <form action="" method="post" class="row" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="col-12">
                    <div class="form-group">
                        <label for="">Tên dịch vụ <span style="color: red">*</span></label>
                        <input type="text" placeholder="Tên dịch vụ" name="tendichvu" id="" class="form-control" value="<?php echo old('tendichvu', $old); ?>">
                        <?php echo form_error('tendichvu', $errors, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Đơn vị tính <span style="color: red">*</span></label>
                        <select name="donvitinh" id="" class="form-select">
                            <option value="">Chọn đơn vị</option>
                            <option value="KWh">KWh</option>
                            <option value="khoi">Khối</option>
                            <option value="nguoi">Người</option>
                            <option value="thang">Tháng</option>
                        </select>
                        <?php echo form_error('donvitinh', $errors, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Giá dịch vụ <span style="color: red">*</span></label>
                        <input type="text" placeholder="Giá dịch vụ" name="giadichvu" id="" class="form-control" value="<?php echo old('giadichvu', $old); ?>">
                        <?php echo form_error('giadichvu', $errors, '<span class="error">', '</span>'); ?>
                    </div>

                </div>
                <div class="form-group">                    
                    <div class="btn-row">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Thêm dịch vụ</button>
                        <a style="margin-left: 20px " href="<?php echo getLinkAdmin('services') ?>" class="btn btn-success"><i class="fa fa-forward"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="box-content">
        <div class="service-left">
            <div class="service-left_top">
                <div>
                    <h3>Quản lý dịch vụ</h3>
                    <i>Các dịch vụ khách thuê xài</i>
                </div>
                <button id="openModalBtn" class="service-btn" style="border: none; color: #fff"><i class="fa fa-plus"></i></button>
                <!-- <a id="openModalBtn" href="#" class="service-btn" style="color: #fff"><i class="fa fa-plus"></i></a> -->
            </div>

            <?php 
                foreach($allService as $item) {
                    ?>
                        <!-- Item 1 -->
                        <div class="service-item">
                            <div class="service-item_left">
                                <div class="service-item_icon">
                                    <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/service-icon.svg" alt="">
                                </div>

                                <div>
                                    <h6><?php echo $item['tendichvu'] ?></h6>
                                    <p><?php echo $item['giadichvu']?>đ/<?php echo $item['donvitinh'] ?></p>
                                    <i>Đang áp dụng cho các phòng</i>
                                </div>
                            </div>

                            <div class="service-item_right">
                                <div class="edit">

                                    <a href="<?php echo getLinkAdmin('services','edit',['id' => $item['id']]); ?>"><img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/service-edit.svg" alt=""></a>
                                 
                                </div>
                                <div class="del">
                                    <a href="<?php echo getLinkAdmin('services','delete',['id' => $item['id']]); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa dịch vụ không ?')"><img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/service-delete.svg" alt=""></a>
                                </div>
                            </div>
                        </div>
                    <?php
                }
                
             ?>
            
        </div>
    <div>
</div>

<?php

layout('footer', 'admin');
?>

<script>
    function toggle(__this){
       let isChecked = __this.checked;
       let checkbox = document.querySelectorAll('input[name="records[]"]');
        for (let index = 0; index < checkbox.length; index++) {
            checkbox[index].checked = isChecked
        }
    }
</script>
