<?php
namespace App\Controllers;

use App\Models\User;
use App\Helpers\SessionManager;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        // Si ya está autenticado, redirigir al dashboard
        if (SessionManager::estaAutenticado()) {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $this->userModel->email = $data['email'];
            $user = $this->userModel->login();

            if ($user && password_verify($data['password'], $user['password'])) {
                SessionManager::guardarUsuario([
                    'user_id' => $user['id'],
                    'username' => $user['username']
                ]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso',
                    'redirect' => '/dashboard'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Credenciales inválidas'
                ]);
            }
            exit;
        }

        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function register() {
        // Si ya está autenticado, redirigir al dashboard
        if (SessionManager::estaAutenticado()) {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $this->userModel->username = $data['username'];
            $this->userModel->email = $data['email'];
            $this->userModel->password = $data['password'];

            if ($this->userModel->registrar()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Registro exitoso',
                    'redirect' => '/login'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error en el registro'
                ]);
            }
            exit;
        }

        require_once __DIR__ . '/../Views/auth/register.php';
    }

    public function logout() {
        SessionManager::cerrarSesion();
        header('Location: /login');
        exit;
    }
}