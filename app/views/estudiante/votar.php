<?php
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'estudiante') {
    header("Location: /backend_ajax/app/login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Estudiante</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f1f1f1;
      padding: 20px;
    }

    .panel {
      background: white;
      padding: 25px;
      border-radius: 10px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }

    h1 {
      color: #444;
    }

    a.logout {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 15px;
      background: #007BFF;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }

    a.logout:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

<div class="panel">
  <h1>Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?> (Estudiante)</h1>
  <p>Desde este panel podrás participar en las votaciones disponibles.</p>

  <a class="logout" href="/backend_ajax/app/controllers/authController.php?logout=1">Cerrar sesión</a>
</div>

</body>
</html>
