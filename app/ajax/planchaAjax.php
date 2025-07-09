<?php
require_once __DIR__ . "/controllers/planchaController.php";
use app\controllers\planchaController;

$insplancha = new planchaController();

if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'crear_plancha':
            echo $insplancha->crearPlanchaControlador();
            break;
            
        case 'modificar_plancha':
            echo $insplancha->modificarPlanchaControlador();
            break;
            
        case 'eliminar_plancha':
            echo $insplancha->eliminarPlanchaControlador();
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
} elseif (isset($_GET['accion'])) {
    switch ($_GET['accion']) {
        case 'obtener_plancha':
            if (isset($_GET['id_opcion_pregunta'])) {
                $plancha = $insplancha->obtenerPlanchaControlador($_GET['id_opcion_pregunta']);
                echo json_encode($plancha ?: false);
            } else {
                echo json_encode(false);
            }
            break;
            
        case 'listar_preguntas':
            $preguntas = $insplancha->obtenerPreguntasDisponibles();
            echo json_encode($preguntas);
            break;
            
        case 'listar_tipos_solicitud':
            $tipos = $insplancha->obtenerTiposSolicitud();
            echo json_encode($tipos);
            break;
            
        case 'obtener_estadisticas':
            $estadisticas = $insplancha->obtenerEstadisticasPlanchas();
            echo json_encode($estadisticas);
            break;
            
        case 'obtener_planchas_por_agrupador':
            $planchas_agrupadas = $insplancha->obtenerPlanchasPorAgrupador();
            echo json_encode($planchas_agrupadas);
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
} else {
    echo json_encode([
        "tipo" => "simple",
        "titulo" => "Error",
        "texto" => "No se especificó ninguna acción",
        "icono" => "error"
    ]);
}
?>
