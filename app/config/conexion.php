<?php
// Cargar constantes de configuracion
require_once __DIR__ . '/server.php';

class Conexion {
    public static function conectar() {
        try {
            $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET NAMES utf8");
            return $pdo;
        } catch (PDOException $e) {
            die("Error al conectar con la base de datos: " . $e->getMessage());
        }
    }
}
