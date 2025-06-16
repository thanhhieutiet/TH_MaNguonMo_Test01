<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container">
    <h1>Thông Báo</h1>
    <div class="alert alert-success">
        Đăng ký học phần thành công! Vui lòng kiểm tra lại thông tin trong giỏ hàng.
    </div>
    <a href="controllers/DangKyController.php?action=cart">Quay Lại Giỏ Hàng</a>
    <a href="controllers/HocPhanController.php?action=index">Tiếp Tục Đăng Ký</a>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>