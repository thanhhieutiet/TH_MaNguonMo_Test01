<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Đăng Ký Học Phần</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="index.php">Hệ Thống Đăng Ký</a>

            <div class="d-flex align-items-center">
                <?php if (isLoggedIn()): ?>
                    <a href="index.php" class="btn btn-outline-secondary me-2">Trang chủ</a>
                    <a href="views/hocphan/index.php" class="btn btn-outline-primary me-2">Học phần đã đăng ký</a>
                    <span class="me-2">Xin chào, <strong><?php echo $_SESSION['user']['ho_ten'] ?? 'Sinh viên'; ?></strong></span>
                    <a href="controllers/AuthController.php?action=logout" class="btn btn-danger">Đăng Xuất</a>
                <?php else: ?>
                    <a href="controllers/AuthController.php?action=showLogin" class="btn btn-primary">Đăng Nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['flash_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['flash_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>