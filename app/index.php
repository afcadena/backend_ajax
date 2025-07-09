<?php
require_once __DIR__ . '/config/session.php';

// Si no está logueado, lo mandamos al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: views/login.php");
    exit;
}

// Definir la vista por defecto para cada rol
$vistaPorDefecto = [
    'admin' => 'crear_votacion',
    'docente' => 'ver_votaciones',
    'estudiante' => 'votar'
];

// Sanitizar parámetro GET
$vista = isset($_GET['view']) ? basename($_GET['view']) : $vistaPorDefecto[$_SESSION['usuario_rol']] ?? '';

// Carpeta del rol actual
$carpetaRol = $_SESSION['usuario_rol'];

// Lista blanca de vistas válidas por rol
$vistasPermitidas = [
    'admin' => ['crear_votacion', 'gestionar_planchas', 'gestionar_votaciones'],
    'docente' => ['ver_votaciones', 'resultados'],
    'estudiante' => ['votar', 'historial']
];

// Validar que esa vista esté permitida
if (in_array($vista, $vistasPermitidas[$carpetaRol])) {
    $rutaVista = __DIR__ . "/views/$carpetaRol/$vista.php";

    if (file_exists($rutaVista)) {
        require_once $rutaVista;
    } else {
        echo "<h2>Error: La vista '$vista' no existe.</h2>";
    }
} else {
    echo "<h2>Error: Vista no permitida para tu rol.</h2>";
}