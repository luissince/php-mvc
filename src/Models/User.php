<?php
namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class User {
    private $conn;
    private $table = 'usuarios';

    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct() {
        try {
            $this->conn = Database::conectar();
        } catch (\Exception $e) {
            // Manejar el error de conexión
            error_log("Error al crear modelo de usuario: " . $e->getMessage());
            throw $e;
        }
    }

    public function registrar() {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      SET username = :username, 
                          email = :email, 
                          password = :password";
            
            $stmt = $this->conn->prepare($query);

            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);

            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":password", $this->password);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function login() {
        try {
            $query = "SELECT * FROM " . $this->table . " 
                      WHERE email = :email LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $this->email);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al iniciar sesión: " . $e->getMessage());
            return false;
        }
    }
}