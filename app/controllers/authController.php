<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/usuarioModel.php';

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../views/login.php");
    exit;
}

// Inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ??'';

    $usuario = usuarioModel::verificarLogin($email);

    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol_nombre'];

        switch ($usuario['rol_nombre']) {
            case 'admin':
                header("Location: ../views/admin/panel.php");
                break;
            case 'docente':
                header("Location: ../views/docente/panel.php");
                break;
            case 'estudiante':
                header("Location: ../views/estudiante/panel.php");
                break;
            default:
                echo "Rol no valido";
        }
    } else {
        echo "Correo o contraseña incorrectos";
    }
}

?>