<?php include __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="/public/css/sinhvien/style.css">

<div class="container">
    <div class="page-header">
        <h1 class="page-title">➕ Thêm Sinh Viên Mới</h1>
        <p class="page-subtitle">Nhập thông tin sinh viên để thêm vào hệ thống</p>
    </div>

    <?php
    $message = getMessage();
    if ($message) {
        echo '<div class="alert alert-' . $message['type'] . '">';
        echo '<i class="icon-' . $message['type'] . '"></i>';
        echo $message['message'];
        echo '</div>';
    }

    if (isset($_SESSION['errors'])) {
        foreach ($_SESSION['errors'] as $error) {
            echo '<div class="alert alert-danger">⚠️ ' . htmlspecialchars($error) . '</div>';
        }
        unset($_SESSION['errors']);
    }
    ?>

    <div class="form-container">
        <form action="controllers/SinhVienController.php?action=store" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label" for="maSV">🆔 Mã Sinh Viên</label>
                <input type="text"
                    id="maSV"
                    name="maSV"
                    class="form-control"
                    value="<?php echo htmlspecialchars($_SESSION['old_input']['maSV'] ?? ''); ?>"
                    placeholder="Nhập mã sinh viên (VD: SV001)"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label" for="hoTen">👤 Họ và Tên</label>
                <input type="text"
                    id="hoTen"
                    name="hoTen"
                    class="form-control"
                    value="<?php echo htmlspecialchars($_SESSION['old_input']['hoTen'] ?? ''); ?>"
                    placeholder="Nhập họ và tên đầy đủ"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label" for="gioiTinh">⚧ Giới Tính</label>
                <select id="gioiTinh" name="gioiTinh" class="form-control" required>
                    <option value="">-- Chọn giới tính --</option>
                    <option value="Nam" <?php echo ($_SESSION['old_input']['gioiTinh'] ?? '') === 'Nam' ? 'selected' : ''; ?>>👨 Nam</option>
                    <option value="Nữ" <?php echo ($_SESSION['old_input']['gioiTinh'] ?? '') === 'Nữ' ? 'selected' : ''; ?>>👩 Nữ</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="ngaySinh">📅 Ngày Sinh</label>
                <input type="date"
                    id="ngaySinh"
                    name="ngaySinh"
                    class="form-control"
                    value="<?php echo htmlspecialchars($_SESSION['old_input']['ngaySinh'] ?? ''); ?>"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label" for="hinh">📷 Hình Ảnh</label>
                <div class="file-input-wrapper">
                    <input type="file"
                        id="hinh"
                        name="hinh"
                        class="file-input"
                        accept="image/*">
                    <label for="hinh" class="file-input-label">
                        📁 Chọn ảnh đại diện (JPG, PNG, GIF)
                    </label>
                </div>
                <small style="color: #666; font-size: 0.85rem;">
                    💡 Tối đa 5MB. Để trống nếu sử dụng ảnh mặc định.
                </small>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">🔒 Mật Khẩu</label>
                <input type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Nhập mật khẩu cho tài khoản"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label" for="maNganh">🎯 Ngành Học</label>
                <select id="maNganh" name="maNganh" class="form-control" required>
                    <option value="">-- Chọn ngành học --</option>
                    <?php foreach ($nganhs as $nganh): ?>
                        <option value="<?php echo htmlspecialchars($nganh['MaNganh']); ?>"
                            <?php echo ($_SESSION['old_input']['maNganh'] ?? '') === $nganh['MaNganh'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($nganh['TenNganh']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="action-buttons">
                <button type="submit" class="btn btn-success">
                    ✅ Thêm Sinh Viên
                </button>
                <a href="controllers/SinhVienController.php?action=index" class="btn btn-secondary">
                    ↩️ Quay Lại
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview ảnh khi chọn file
    document.getElementById('hinh').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const label = document.querySelector('.file-input-label');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                label.innerHTML = `
                <img src="${e.target.result}" style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;"><br>
                📁 ${file.name}
            `;
            };
            reader.readAsDataURL(file);
        } else {
            label.innerHTML = '📁 Chọn ảnh đại diện (JPG, PNG, GIF)';
        }
    });
</script>

<?php
include __DIR__ . '/../layout/footer.php';
unset($_SESSION['old_input']);
?>