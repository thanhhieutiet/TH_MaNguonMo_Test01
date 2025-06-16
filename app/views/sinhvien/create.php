<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container">
    <h1>Thêm Sinh Viên</h1>
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
    <form action="controllers/SinhVienController.php?action=store" method="post" enctype="multipart/form-data">
        <div>
            <label>Mã SV:</label>
            <input type="text" name="maSV" value="<?php echo $_SESSION['old_input']['maSV'] ?? ''; ?>" required>
        </div>
        <div>
            <label>Họ Tên:</label>
            <input type="text" name="hoTen" value="<?php echo $_SESSION['old_input']['hoTen'] ?? ''; ?>" required>
        </div>
        <div>
            <label>Giới Tính:</label>
            <select name="gioiTinh" required>
                <option value="Nam" <?php echo ($_SESSION['old_input']['gioiTinh'] ?? '') === 'Nam' ? 'selected' : ''; ?>>Nam</option>
                <option value="Nữ" <?php echo ($_SESSION['old_input']['gioiTinh'] ?? '') === 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
            </select>
        </div>
        <div>
            <label>Ngày Sinh:</label>
            <input type="date" name="ngaySinh" value="<?php echo $_SESSION['old_input']['ngaySinh'] ?? ''; ?>" required>
        </div>
        <div>
            <label>Hình Ảnh:</label>
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
                    <option value="<?php echo $nganh['MaNganh']; ?>" <?php echo ($_SESSION['old_input']['maNganh'] ?? '') === $nganh['MaNganh'] ? 'selected' : ''; ?>>
                        <?php echo $nganh['TenNganh']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Thêm</button>
    </form>
    <a href="controllers/SinhVienController.php?action=index">Quay Lại</a>
</div>
<?php include __DIR__ . '/../layouts/footer.php';
unset($_SESSION['old_input']); ?>