<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Đăng Nhập</h3>
            </div>
            <div class="card-body">
                <?php
                // Hiển thị lỗi nếu có
                if (isset($_SESSION['errors'])) {
                    foreach ($_SESSION['errors'] as $error) {
                        echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
                    }
                    unset($_SESSION['errors']);
                }
                ?>

                <form action="controllers/AuthController.php?action=login" method="post">
                    <div class="mb-3">
                        <label for="maSV" class="form-label">Mã Sinh Viên:</label>
                        <input type="text"
                            class="form-control"
                            id="maSV"
                            name="maSV"
                            value="<?php echo htmlspecialchars($_SESSION['old_input']['maSV'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật Khẩu:</label>
                        <input type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../layout/footer.php';
unset($_SESSION['old_input']);
?>