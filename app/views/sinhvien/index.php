<?php include __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="/TTMH_Test01/public/css/index.css">



<div class="sv-wrapper">
    <h1 class="sv-title">Danh Sách Sinh Viên</h1>

    <?php
    $message = getMessage();
    if ($message) {
        echo '<div class="sv-alert sv-alert-' . $message['type'] . '">' . $message['message'] . '</div>';
    }
    ?>

    <div class="sv-header-action">
        <a href="controllers/SinhVienController.php?action=create" class="sv-btn sv-btn-primary">Thêm Sinh Viên</a>
    </div>

    <div class="sv-grid">
        <?php foreach ($sinhViens as $sv): ?>
            <div class="sv-card">
                <div class="sv-img-wrapper">
                    <img src="<?php echo $sv['Hinh'] ?: 'public/images/default.png'; ?>" alt="Ảnh sinh viên">

                </div>
                <div class="sv-info">
                    <h3><?php echo $sv['HoTen']; ?></h3>
                    <p><strong>Mã SV:</strong> <?php echo $sv['MaSV']; ?></p>
                    <p><strong>Giới Tính:</strong> <?php echo $sv['GioiTinh']; ?></p>
                    <p><strong>Ngày Sinh:</strong> <?php echo formatDate($sv['NgaySinh']); ?></p>
                    <p><strong>Ngành:</strong> <?php echo $sv['TenNganh']; ?></p>
                </div>
                <div class="sv-actions">
                    <a href="controllers/SinhVienController.php?action=edit&id=<?php echo $sv['MaSV']; ?>" class="sv-action">Sửa</a>
                    <a href="controllers/SinhVienController.php?action=delete&id=<?php echo $sv['MaSV']; ?>" class="sv-action sv-delete" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                    <a href="controllers/SinhVienController.php?action=detail&id=<?php echo $sv['MaSV']; ?>" class="sv-action">Chi Tiết</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="sv-pagination">
        <?php echo $pagination; ?>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>