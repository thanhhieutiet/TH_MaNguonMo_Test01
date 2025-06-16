<?php
// Định nghĩa đường dẫn gốc đến thư mục "app"
define('APP_PATH', __DIR__ . '/app');

// Include các file cần thiết
require_once APP_PATH . '/includes/session.php';
require_once APP_PATH . '/includes/functions.php';

// Lấy URL từ query string
$url = $_GET['url'] ?? '';
$segments = explode('/', trim($url, '/'));

// Lấy tên Controller và action từ URL
$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'SinhVienController';
$action = !empty($segments[1]) ? $segments[1] : 'index';
$params = array_slice($segments, 2);

// Xác định file Controller cần load
$controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';

// Gọi controller tương ứng nếu tồn tại
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerInstance = new $controllerName();

    if (method_exists($controllerInstance, $action)) {
        call_user_func_array([$controllerInstance, $action], $params);
    } else {
        // Nếu không có action, gọi mặc định là index()
        $controllerInstance->index();
    }
} else {
    // Nếu controller không tồn tại, gọi về controller mặc định
    require_once APP_PATH . '/controllers/SinhVienController.php';
    $controller = new SinhVienController();
    $controller->index();
}
