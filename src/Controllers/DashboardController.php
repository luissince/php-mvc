<?php
namespace App\Controllers;

use App\Config\Database;
use App\Helpers\SessionManager;
use PDO;

class DashboardController {
    private $conn;

    public function __construct() {
        $this->conn = Database::conectar();
    }

    public function index() {
        // Verificar si el usuario NO está autenticado
        if (!SessionManager::estaAutenticado()) {
            header('Location: /login');
            exit;
        }

        // Si es una solicitud AJAX para obtener usuarios
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->obtenerUsuarios();
            exit;
        }

        // Obtener datos del usuario para la vista
        $usuario = SessionManager::obtenerUsuario();

        // Renderizar vista de dashboard
        require_once __DIR__ . '/../Views/dashboard/index.php';
    }

    private function obtenerUsuarios() {
        try {
            $query = "SELECT id, username, email, created_at FROM usuarios ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'usuarios' => $usuarios
            ]);
        } catch (\PDOException $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener usuarios: ' . $e->getMessage()
            ]);
        }
    }

    public function eliminarUsuario() {
        // Verificar autenticación
        if (!SessionManager::estaAutenticado()) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit;
        }

        // Procesar solicitud de eliminación via AJAX
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $usuarioId = $data['id'] ?? null;

            if ($usuarioId) {
                try {
                    $query = "DELETE FROM usuarios WHERE id = :id";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':id', $usuarioId, PDO::PARAM_INT);
                    $resultado = $stmt->execute();

                    echo json_encode([
                        'success' => $resultado,
                        'message' => $resultado ? 'Usuario eliminado' : 'Error al eliminar'
                    ]);
                } catch (\PDOException $e) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error: ' . $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de usuario no válido'
                ]);
            }
            exit;
        }
    }
}