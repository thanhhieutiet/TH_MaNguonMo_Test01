<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container">
    <h1>Đăng Ký Học Phần</h1>
    <?php
    $message = getMessage();
    if ($message) {
        echo '<div class="alert alert-' . $message['type'] . '">' . $message['message'] . '</div>';
    }
    ?>
    <table border="1">
        <thead>
            <tr>
                <th>Mã HP</th>
                <th>Tên HP</th>
                <th>Số Tín Chỉ</th>
                <th>Số Lượng Còn Lại</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hocPhans as $hp): ?>
                <tr>
                    <td><?php echo $hp['MaHP']; ?></td>
                    <td><?php echo $hp['TenHP']; ?></td>
                    <td><?php echo $hp['SoTinChi']; ?></td>
                    <td><?php echo $hp['SoLuongConLai']; ?></td>
                    <td>
                        <a href="controllers/HocPhanController.php?action=addToCart&id=<?php echo $hp['MaHP']; ?>" onclick="return confirm('Thêm học phần vào giỏ hàng?');">Thêm</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="controllers/DangKyController.php?action=cart">Xem Giỏ Hàng (<?php echo getCartCount(); ?>)</a>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>