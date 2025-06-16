<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Học Phần</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Hệ Thống Đăng Ký</a>
            <?php if (isLoggedIn()): ?>
                <a href="controllers/AuthController.php?action=logout" class="btn btn-danger">Đăng Xuất</a>
            <?php else: ?>
                <a href="controllers/AuthController.php?action=showLogin" class="btn btn-primary">Đăng Nhập</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container mt-4">