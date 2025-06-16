<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container">
    <h1>Chi Tiết Sinh Viên</h1>
    <table border="1">
        <tr>
            <th>Mã SV</th>
            <td><?php echo $sinhVien['MaSV']; ?></td>
        </tr>
        <tr>
            <th>Họ Tên</th>
            <td><?php echo $sinhVien['HoTen']; ?></td>
        </tr>
        <tr>
            <th>Giới Tính</th>
            <td><?php echo $sinhVien['GioiTinh']; ?></td>
        </tr>
        <tr>
            <th>Ngày Sinh</th>
            <td><?php echo formatDate($sinhVien['NgaySinh']); ?></td>
        </tr>
        <tr>
            <th>Hình Ảnh</th>
            <td><img src="<?php echo $sinhVien['Hinh']; ?>" width="100" alt="Hình sinh viên"></td>
        </tr>
        <tr>
            <th>Ngành Học</th>
            <td><?php echo $sinhVien['TenNganh']; ?></td>
        </tr>
    </table>
    <a href="controllers/SinhVienController.php?action=index">Quay Lại</a>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>