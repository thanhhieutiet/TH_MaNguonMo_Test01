<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <h1>Sửa Thông Tin Sinh Viên</h1>
    <?php
    $message = getMessage();
    if ($message) {
        echo '<div class="alert alert-' . $message['type'] . '">' . $message['message'] . '</div>';
    }
    if (isset($_SESSION['errors'])) {
        foreach ($_SESSION['errors'] as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        unset($_SESSION['errors']);
    }
    ?>
    <form action="controllers/SinhVienController.php?action=update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="maSV" value="<?php echo $sinhVien['MaSV']; ?>">
        <div>
            <label>Họ Tên:</label>
            <input type="text" name="hoTen" value="<?php echo $_SESSION['old_input']['hoTen'] ?? $sinhVien['HoTen']; ?>" required>
        </div>
        <div>
            <label>Giới Tính:</label>
            <select name="gioiTinh" required>
                <option value="Nam" <?php echo ($_SESSION['old_input']['gioiTinh'] ?? $sinhVien['GioiTinh']) === 'Nam' ? 'selected' : ''; ?>>Nam</option>
                <option value="Nữ" <?php echo ($_SESSION['old_input']['gioiTinh'] ?? $sinhVien['GioiTinh']) === 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
            </select>
        </div>
        <div>
            <label>Ngày Sinh:</label>
            <input type="date" name="ngaySinh" value="<?php echo $_SESSION['old_input']['ngaySinh'] ?? $sinhVien['NgaySinh']; ?>" required>
        </div>
        <div>
            <label>Hình Ảnh Hiện Tại:</label>
            <img src="<?php echo $sinhVien['Hinh']; ?>" width="100" alt="Hình hiện tại">
            <input type="hidden" name="oldHinh" value="<?php echo $sinhVien['Hinh']; ?>">
            <input type="file" name="hinh">
        </div>
        <div>
            <label>Mật Khẩu:</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <label>Ngành Học:</label>
            <select name="maNganh" required>
                <?php foreach ($nganhs as $nganh): ?>
                    <option value="<?php echo $nganh['MaNganh']; ?>" <?php echo ($_SESSION['old_input']['maNganh'] ?? $sinhVien['MaNganh']) === $nganh['MaNganh'] ? 'selected' : ''; ?>>
                        <?php echo $nganh['TenNganh']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Cập Nhật</button>
    </form>
    <a href="controllers/SinhVienController.php?action=index">Quay Lại</a>
</div>
<?php include __DIR__ . '/../layout/footer.php';
unset($_SESSION['old_input']); ?>