<?php
require_once "../../config/server.php";
require_once "controllers/planchaController.php";

use app\controllers\planchaController;

if (isset($_POST['accion'])) {
    $insPlancha = new planchaController();
    
    switch ($_POST['accion']) {
        case 'crear_plancha':
            echo $insPlancha->crearPlanchaControlador();
            break;
            
        case 'modificar_plancha':
            echo $insPlancha->modificarPlanchaControlador();
            break;
            
        case 'eliminar_plancha':
            echo $insPlancha->eliminarPlanchaControlador();
            break;
            
        case 'asociar_plancha':
            echo $insPlancha->asociarPlanchaVotacionControlador();
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
    $insPlancha = new planchaController();
    
    switch ($_GET['accion']) {
        case 'obtener_plancha':
            if (isset($_GET['id_plancha'])) {
                $plancha = $insPlancha->obtenerPlanchaControlador($_GET['id_plancha']);
                echo json_encode($plancha);
            }
            break;
            
        case 'listar_planchas':
            $planchas = $insPlancha->listarPlanchasControlador();
            echo json_encode($planchas);
            break;
            
        case 'obtener_planchas_votacion':
            if (isset($_GET['id_votacion'])) {
                $planchas = $insPlancha->obtenerPlanchasVotacion($_GET['id_votacion']);
                echo json_encode($planchas);
            }
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
        "texto" => "Datos incompletos",
        "icono" => "error"
    ]);
}
?>
