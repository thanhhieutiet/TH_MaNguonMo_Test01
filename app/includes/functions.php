<?php
// Format ngày tháng
function formatDate($date, $format = 'd/m/Y')
{
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

// Upload file ảnh
function uploadImage($file, $uploadDir = 'Content/images/')
{
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }

    $maxSize = 2 * 1024 * 1024; // 2MB
    if ($file['size'] > $maxSize) {
        return false;
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = uniqid() . '.' . $extension;
    $uploadPath = $uploadDir . $fileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return '/' . $uploadPath;
    }

    return false;
}

// Validate dữ liệu
function validateRequired($value, $fieldName)
{
    if (empty(trim($value))) {
        return "$fieldName không được để trống";
    }
    return '';
}

function validateEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Email không hợp lệ";
    }
    return '';
}

function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    if (!$d || $d->format('Y-m-d') !== $date) {
        return "Ngày sinh không hợp lệ";
    }
    return '';
}

// Pagination
function getPagination($currentPage, $totalRecords, $recordsPerPage, $baseUrl)
{
    $totalPages = ceil($totalRecords / $recordsPerPage);

    if ($totalPages <= 1) {
        return '';
    }

    $pagination = '<nav><ul class="pagination justify-content-center">';

    // Previous page
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        $pagination .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $prevPage . '">Trước</a></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $currentPage) ? ' active' : '';
        $pagination .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
    }

    // Next page
    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;
        $pagination .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $nextPage . '">Sau</a></li>';
    }

    $pagination .= '</ul></nav>';

    return $pagination;
}

// Clean input
function cleanInput($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

// Generate password hash
function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verifyPassword($password, $hash)
{
    return password_verify($password, $hash);
}
