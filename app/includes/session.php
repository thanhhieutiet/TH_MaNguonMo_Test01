<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
function isLoggedIn()
{
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

// Lấy thông tin user đăng nhập
function getCurrentUser()
{
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

// Đăng nhập
function login($userData)
{
    $_SESSION['user'] = $userData;
}

// Đăng xuất
function logout()
{
    unset($_SESSION['user']);
    if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }
    session_destroy();
}

// Quản lý giỏ hàng
function getCart()
{
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

function addToCart($maHP)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (!in_array($maHP, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $maHP;
        return true;
    }
    return false;
}

function removeFromCart($maHP)
{
    if (isset($_SESSION['cart'])) {
        $key = array_search($maHP, $_SESSION['cart']);
        if ($key !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
            return true;
        }
    }
    return false;
}

function clearCart()
{
    unset($_SESSION['cart']);
}

function getCartCount()
{
    return isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
}

// Redirect với message
function redirect($url, $message = '', $type = 'success')
{
    if (!empty($message)) {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
    }
    header("Location: $url");
    exit();
}

// Lấy và xóa message
function getMessage()
{
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}
