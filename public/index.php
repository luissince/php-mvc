<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Helpers\SessionManager;

// Iniciar sesión una vez al principio
SessionManager::iniciarSesion();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routes = [
    '/' => ['controller' => 'AuthController', 'method' => 'login'],
    '/login' => ['controller' => 'AuthController', 'method' => 'login'],
    '/register' => ['controller' => 'AuthController', 'method' => 'register'],
    '/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
    '/dashboard' => ['controller' => 'DashboardController', 'method' => 'index'],
    '/dashboard/eliminar-usuario' => ['controller' => 'DashboardController', 'method' => 'eliminarUsuario']
];

$controllerName = $routes[$uri]['controller'] ?? null;
$methodName = $routes[$uri]['method'] ?? null;

if ($controllerName && $methodName) {
    $controllerClass = "App\\Controllers\\{$controllerName}";
    $controller = new $controllerClass();
    
    if (method_exists($controller, $methodName)) {
        $controller->$methodName();
    } else {
        http_response_code(404);
        echo "Página no encontrada";
    }
} else {
    http_response_code(404);
    echo "Página no encontrada";
}