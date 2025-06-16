<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <h1>Giỏ hàng</h1>
    <?php
    $message = getMessage();
    if ($message) {
        echo '<div class="alert alert-' . $message['type'] . '">' . $message['message'] . '</div>';
    }
    ?>

    <?php if (empty($danhSachHP)): ?>
        <p>Giỏ hàng trống.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Mã HP</th>
                    <th>Tên HP</th>
                    <th>Số Tín Chỉ</th>
                    <th>Số Lượng Còn Lại</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($danhSachHP as $hp): ?>
                    <tr>
                        <td><?php echo $hp['MaHP']; ?></td>
                        <td><?php echo $hp['TenHP']; ?></td>
                        <td><?php echo $hp['SoTinChi']; ?></td>
                        <td><?php echo $hp['SoLuongConLai']; ?></td>
                        <td>
                            <a href="controllers/DangKyController.php?action=removeFromCart&id=<?php echo $hp['MaHP']; ?>">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form action="controllers/DangKyController.php?action=saveRegistration" method="post">
            <button type="submit">Lưu Đăng Ký</button>
        </form>
        <a href="controllers/DangKyController.php?action=clearCart">Xóa Tất Cả</a>
    <?php endif; ?>

    <a href="controllers/HocPhanController.php?action=index">Quay lại chọn học phần</a>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>