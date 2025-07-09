<?php
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'docente') {
    header("Location: ../login.php");
    exit;
}
?>

<h1>Panel de Docente</h1>
<p>Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?>.</p>
<a href="../../controllers/authController.php?logout=1">Cerrar sesión</a>
