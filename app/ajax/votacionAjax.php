<?php
// Mostrar errores en desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Encabezado para que las respuestas sean JSON válidas
header("Content-Type: application/json");

// Cargar archivo de configuración y controlador
require_once(__DIR__ . "/../config/server.php");

require_once(__DIR__ . "/../controllers/votacionController.php");

use app\controllers\votacionController;

// Verificar si la solicitud es por POST
if (isset($_POST['accion'])) {
    $insVotacion = new votacionController();

    switch ($_POST['accion']) {
        case 'crear_votacion':
            echo $insVotacion->crearVotacionControlador();
            break;

        case 'modificar_votacion':
            echo $insVotacion->modificarVotacionControlador();
            break;

        case 'eliminar_votacion':
            echo $insVotacion->eliminarVotacionControlador();
            break;

        default:
            echo json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "Acción no válida",
                "icono" => "error"
            ]);
            break;
    }

// Verificar si la solicitud es por GET
} elseif (isset($_GET['accion'])) {
    $insVotacion = new votacionController();

    switch ($_GET['accion']) {
        case 'obtener_votacion':
            if (isset($_GET['id_votacion'])) {
                $votacion = $insVotacion->obtenerVotacionControlador($_GET['id_votacion']);
                echo json_encode($votacion);
            }
            break;

        case 'listar_votaciones':
            $votaciones = $insVotacion->listarVotacionesControlador();
            echo json_encode($votaciones);
            break;

        default:
            echo json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "Acción no válida",
                "icono" => "error"
            ]);
            break;
    }

// Solicitud POST sin "accion", intenta crear votación básica
} else {
    if (isset($_POST['titulo']) && isset($_POST['fecha_inicio'])) {
        $insVotacion = new votacionController();
        echo $insVotacion->crearVotacionControlador();
    } else {
        echo json_encode([
            "tipo" => "simple",
            "titulo" => "Error",
            "texto" => "Datos incompletos",
            "icono" => "error"
        ]);
    }
}
