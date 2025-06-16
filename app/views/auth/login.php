<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <h1>Đăng Nhập</h1>
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
    <form action="controllers/AuthController.php?action=login" method="post">
        <div>
            <label>Mã Sinh Viên:</label>
            <input type="text" name="maSV" value="<?php echo $_SESSION['old_input']['maSV'] ?? ''; ?>" required>
        </div>
        <div>
            <label>Mật Khẩu:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Đăng Nhập</button>
    </form>
</div>
<?php include __DIR__ . '/../layouts/footer.php';
unset($_SESSION['old_input']); ?>