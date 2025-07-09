<?php
require_once __DIR__ . '/../config/conexion.php';

class usuarioModel {
    public static function verificarLogin($email) {
        $pdo = Conexion::conectar();
        
       
         $sql = "SELECT u.*, r.nombre AS rol_nombre
            FROM usuarios u
            JOIN roles r ON u.rol_id = r.id
            WHERE u.email = :email LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>