<?php include __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="/public/css/style.css">

<div class="container">
    <div class="page-header">
        <h1 class="page-title">🎓 Quản Lý Sinh Viên</h1>
        <p class="page-subtitle">Hệ thống quản lý thông tin sinh viên hiện đại</p>
    </div>

    <?php
    $message = getMessage();
    if ($message) {
        echo '<div class="alert alert-' . $message['type'] . '">';
        echo '<i class="icon-' . $message['type'] . '"></i>';
        echo $message['message'];
        echo '</div>';
    }
    ?>

    <div class="header-actions">
        <a href="controllers/SinhVienController.php?action=create" class="btn btn-primary">
            ➕ Thêm Sinh Viên Mới
        </a>
    </div>

    <div class="sv-grid">
        <?php foreach ($sinhViens as $sv): ?>
            <div class="sv-card">
                <div class="sv-img-wrapper">
                    <?php
                    $hinh = $sv['Hinh'] ?: 'public/uploads/sinhvien/default.png';
                    ?>
                    <img src="/<?php echo $hinh; ?>" alt="Ảnh sinh viên <?php echo htmlspecialchars($sv['HoTen']); ?>">
                </div>

                <div class="sv-info">
                    <h3><?php echo htmlspecialchars($sv['HoTen']); ?></h3>
                    <p><strong>🆔 Mã SV:</strong> <?php echo htmlspecialchars($sv['MaSV']); ?></p>
                    <p><strong>👤 Giới Tính:</strong> <?php echo htmlspecialchars($sv['GioiTinh']); ?></p>
                    <p><strong>📅 Ngày Sinh:</strong> <?php echo formatDate($sv['NgaySinh']); ?></p>
                    <p><strong>🎯 Ngành:</strong> <?php echo htmlspecialchars($sv['TenNganh']); ?></p>
                </div>

                <div class="sv-actions">
                    <a href="controllers/SinhVienController.php?action=edit&id=<?php echo urlencode($sv['MaSV']); ?>"
                        class="sv-action" title="Chỉnh sửa thông tin">
                        ✏️ Sửa
                    </a>
                    <a href="controllers/SinhVienController.php?action=delete&id=<?php echo urlencode($sv['MaSV']); ?>"
                        class="sv-action sv-delete"
                        onclick="return confirm('⚠️ Bạn có chắc chắn muốn xóa sinh viên này?\n\nHành động này không thể hoàn tác!');"
                        title="Xóa sinh viên">
                        🗑️ Xóa
                    </a>
                    <a href="controllers/SinhVienController.php?action=detail&id=<?php echo urlencode($sv['MaSV']); ?>"
                        class="sv-action" title="Xem chi tiết">
                        👁️ Chi Tiết
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($sinhViens)): ?>
        <div class="card" style="text-align: center; padding: 60px;">
            <h3 style="color: #666; margin-bottom: 15px;">📚 Chưa có sinh viên nào</h3>
            <p style="color: #999; margin-bottom: 25px;">Hãy thêm sinh viên đầu tiên vào hệ thống</p>
            <a href="controllers/SinhVienController.php?action=create" class="btn btn-primary">
                ➕ Thêm Sinh Viên Đầu Tiên
            </a>
        </div>
    <?php endif; ?>

    <?php if (!empty($pagination)): ?>
        <div class="pagination">
            <?php echo $pagination; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>