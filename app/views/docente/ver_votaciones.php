<?php
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'docente') {
    header("Location: /backend_ajax/app/login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Docente</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eef2f3;
      padding: 20px;
    }

    .panel {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      max-width: 700px;
      margin: auto;
    }

    h1 {
      color: #333;
    }

    a.logout {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #fff;
      background: #dc3545;
      padding: 10px 15px;
      border-radius: 5px;
    }

    a.logout:hover {
      background: #c82333;
    }
  </style>
</head>
<body>

<div class="panel">
  <h1>Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?> (Docente)</h1>
  <p>Aquí podrás ver y gestionar las votaciones de tus estudiantes.</p>

  <a class="logout" href="/backend_ajax/app/controllers/authController.php?logout=1">Cerrar sesión</a>
</div>

</body>
</html>
