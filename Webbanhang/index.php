<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
require_once 'app/config/database.php';
$database = new Database();
$db = $database->getConnection();

// Get URL and process routing
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

// Default route when no active session
if (!isset($_SESSION['user_id']) && $controller != 'auth') {
    header("Location: index.php?controller=auth&action=login");
    exit();
}

// Convert controller name to proper format
$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/app/controllers/' . $controllerName . '.php';

// Check if controller exists
if (!file_exists($controllerFile)) {
    die("Controller không tồn tại: " . $controllerName);
}

// Include required models based on controller
require_once $controllerFile;

// Initialize controller with database connection
$controllerInstance = new $controllerName($db);

// Check if action exists
if (!method_exists($controllerInstance, $action)) {
    die("Action không tồn tại: " . $action);
}

// Process any additional parameters
$params = [];
if (isset($_GET['id'])) {
    $params[] = $_GET['id'];
}

// Execute the action
call_user_func_array([$controllerInstance, $action], $params);

?>