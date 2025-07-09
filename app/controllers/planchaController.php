<?php
namespace app\controllers;
use app\models\mainModel;

class planchaController extends mainModel {
    
    # Controlador para crear plancha #
    public function crearPlanchaControlador() {
        $nombre_plancha = $this->limpiarCadena($_POST['nombre_plancha']);
        $descripcion = $this->limpiarCadena($_POST['descripcion']);
        $color_plancha = $this->limpiarCadena($_POST['color_plancha']);

        // Validar campos obligatorios
        if ($nombre_plancha == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Campo incompleto",
                "texto" => "El nombre de la plancha es obligatorio",
                "icono" => "error"
            ]);
        }

        // Verificar que no exista una plancha con el mismo nombre
        $verificar_nombre = $this->ejecutarConsulta("SELECT ID_PLANCHA FROM ugc_planchas WHERE NOMBRE_PLANCHA = '$nombre_plancha'");
        if ($verificar_nombre->rowCount() > 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Nombre duplicado",
                "texto" => "Ya existe una plancha con este nombre",
                "icono" => "error"
            ]);
        }

        $datos_plancha = [
            ["campo_nombre" => "NOMBRE_PLANCHA", "campo_marcador" => ":NombrePlancha", "campo_valor" => $nombre_plancha],
            ["campo_nombre" => "DESCRIPCION", "campo_marcador" => ":Descripcion", "campo_valor" => $descripcion],
            ["campo_nombre" => "COLOR_PLANCHA", "campo_marcador" => ":ColorPlancha", "campo_valor" => $color_plancha]
        ];

        $insertar_plancha = $this->guardarDatos("ugc_planchas", $datos_plancha);
        
        if ($insertar_plancha->rowCount() >= 1) {
            return json_encode([
                "tipo" => "limpiar",
                "titulo" => "Plancha creada",
                "texto" => "La plancha ha sido creada correctamente",
                "icono" => "success"
            ]);
        } else {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error inesperado",
                "texto" => "No se pudo crear la plancha",
                "icono" => "error"
            ]);
        }
    }

    # Controlador para modificar plancha #
    public function modificarPlanchaControlador() {
        $id_plancha = $this->limpiarCadena($_POST['id_plancha']);
        $nombre_plancha = $this->limpiarCadena($_POST['nombre_plancha']);
        $descripcion = $this->limpiarCadena($_POST['descripcion']);
        $color_plancha = $this->limpiarCadena($_POST['color_plancha']);

        // Validar campos obligatorios
        if ($id_plancha == "" || $nombre_plancha == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Campos incompletos",
                "texto" => "ID y nombre de plancha son obligatorios",
                "icono" => "error"
            ]);
        }

        // Verificar que la plancha existe
        $verificar_plancha = $this->ejecutarConsulta("SELECT ID_PLANCHA FROM ugc_planchas WHERE ID_PLANCHA = '$id_plancha'");
        if ($verificar_plancha->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Plancha no encontrada",
                "texto" => "La plancha que intenta modificar no existe",
                "icono" => "error"
            ]);
        }

        $datos_actualizacion = [
            ["campo_nombre" => "NOMBRE_PLANCHA", "campo_marcador" => ":NombrePlancha", "campo_valor" => $nombre_plancha],
            ["campo_nombre" => "DESCRIPCION", "campo_marcador" => ":Descripcion", "campo_valor" => $descripcion],
            ["campo_nombre" => "COLOR_PLANCHA", "campo_marcador" => ":ColorPlancha", "campo_valor" => $color_plancha],
            ["campo_nombre" => "FECHA_MODIFICACION", "campo_marcador" => ":FechaModificacion", "campo_valor" => date("Y-m-d H:i:s")]
        ];

        $condicion = [
            "condicion_campo" => "ID_PLANCHA",
            "condicion_marcador" => ":IdPlancha",
            "condicion_valor" => $id_plancha
        ];

        $actualizar_plancha = $this->actualizarDatos("ugc_planchas", $datos_actualizacion, $condicion);

        if ($actualizar_plancha->rowCount() >= 1) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Plancha modificada",
                "texto" => "La plancha ha sido modificada correctamente",
                "icono" => "success"
            ]);
        } else {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Sin cambios",
                "texto" => "No se realizaron cambios en la plancha",
                "icono" => "info"
            ]);
        }
    }

    # Controlador para eliminar plancha #
    public function eliminarPlanchaControlador() {
        $id_plancha = $this->limpiarCadena($_POST['id_plancha']);

        if ($id_plancha == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "ID de plancha requerido",
                "icono" => "error"
            ]);
        }

        // Verificar que la plancha existe
        $verificar_plancha = $this->ejecutarConsulta("SELECT ID_PLANCHA FROM ugc_planchas WHERE ID_PLANCHA = '$id_plancha'");
        if ($verificar_plancha->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Plancha no encontrada",
                "texto" => "La plancha que intenta eliminar no existe",
                "icono" => "error"
            ]);
        }

        // Verificar si la plancha está asociada a alguna votación
        $verificar_asociacion = $this->ejecutarConsulta("SELECT ID_VOTACION_PLANCHA FROM ugc_votacion_planchas WHERE ID_PLANCHA = '$id_plancha'");
        if ($verificar_asociacion->rowCount() > 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Eliminación no permitida",
                "texto" => "No se puede eliminar una plancha que está asociada a votaciones",
                "icono" => "error"
            ]);
        }

        $eliminar_plancha = $this->eliminarRegistro("ugc_planchas", "ID_PLANCHA", $id_plancha);

        if ($eliminar_plancha->rowCount() >= 1) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Plancha eliminada",
                "texto" => "La plancha ha sido eliminada correctamente",
                "icono" => "success"
            ]);
        } else {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error inesperado",
                "texto" => "No se pudo eliminar la plancha",
                "icono" => "error"
            ]);
        }
    }

    # Controlador para asociar plancha a votación #
    public function asociarPlanchaVotacionControlador() {
        $id_votacion = $this->limpiarCadena($_POST['id_votacion']);
        $id_plancha = $this->limpiarCadena($_POST['id_plancha']);
        $orden_plancha = $this->limpiarCadena($_POST['orden_plancha']);

        // Validar campos obligatorios
        if ($id_votacion == "" || $id_plancha == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Campos incompletos",
                "texto" => "Votación y plancha son obligatorios",
                "icono" => "error"
            ]);
        }

        // Verificar que la votación existe y está en estado PENDIENTE
        $verificar_votacion = $this->ejecutarConsulta("SELECT ESTADO FROM ugc_votaciones WHERE ID_VOTACION = '$id_votacion'");
        if ($verificar_votacion->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Votación no encontrada",
                "texto" => "La votación seleccionada no existe",
                "icono" => "error"
            ]);
        }

        $estado_votacion = $verificar_votacion->fetch(\PDO::FETCH_ASSOC);
        if ($estado_votacion['ESTADO'] != 'PENDIENTE') {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Asociación no permitida",
                "texto" => "Solo se pueden asociar planchas a votaciones pendientes",
                "icono" => "error"
            ]);
        }

        // Verificar que la plancha existe
        $verificar_plancha = $this->ejecutarConsulta("SELECT ID_PLANCHA FROM ugc_planchas WHERE ID_PLANCHA = '$id_plancha' AND ESTADO = 'ACTIVO'");
        if ($verificar_plancha->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Plancha no encontrada",
                "texto" => "La plancha seleccionada no existe o está inactiva",
                "icono" => "error"
            ]);
        }

        // Verificar que no esté ya asociada
        $verificar_asociacion = $this->ejecutarConsulta("SELECT ID_VOTACION_PLANCHA FROM ugc_votacion_planchas WHERE ID_VOTACION = '$id_votacion' AND ID_PLANCHA = '$id_plancha'");
        if ($verificar_asociacion->rowCount() > 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Asociación duplicada",
                "texto" => "Esta plancha ya está asociada a la votación",
                "icono" => "error"
            ]);
        }

        $datos_asociacion = [
            ["campo_nombre" => "ID_VOTACION", "campo_marcador" => ":IdVotacion", "campo_valor" => $id_votacion],
            ["campo_nombre" => "ID_PLANCHA", "campo_marcador" => ":IdPlancha", "campo_valor" => $id_plancha],
            ["campo_nombre" => "ORDEN_PLANCHA", "campo_marcador" => ":OrdenPlancha", "campo_valor" => $orden_plancha]
        ];

        $insertar_asociacion = $this->guardarDatos("ugc_votacion_planchas", $datos_asociacion);
        
        if ($insertar_asociacion->rowCount() >= 1) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Plancha asociada",
                "texto" => "La plancha ha sido asociada correctamente a la votación",
                "icono" => "success"
            ]);
        } else {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error inesperado",
                "texto" => "No se pudo asociar la plancha a la votación",
                "icono" => "error"
            ]);
        }
    }

    # Método para listar planchas #
    public function listarPlanchasControlador() {
        $consulta = $this->ejecutarConsulta("
            SELECT p.*, 
                   COUNT(vp.ID_VOTACION) as total_votaciones
            FROM ugc_planchas p
            LEFT JOIN ugc_votacion_planchas vp ON p.ID_PLANCHA = vp.ID_PLANCHA
            GROUP BY p.ID_PLANCHA
            ORDER BY p.FECHA_CREACION DESC
        ");
        return $consulta->fetchAll(\PDO::FETCH_ASSOC);
    }

    # Método para obtener datos de plancha específica #
    public function obtenerPlanchaControlador($id_plancha) {
        $id_plancha = $this->limpiarCadena($id_plancha);
        $consulta = $this->ejecutarConsulta("SELECT * FROM ugc_planchas WHERE ID_PLANCHA = '$id_plancha'");
        return $consulta->fetch(\PDO::FETCH_ASSOC);
    }

    # Método para obtener planchas disponibles para asociar #
    public function obtenerPlanchasDisponibles() {
        $consulta = $this->ejecutarConsulta("SELECT * FROM ugc_planchas WHERE ESTADO = 'ACTIVO' ORDER BY NOMBRE_PLANCHA ASC");
        return $consulta->fetchAll(\PDO::FETCH_ASSOC);
    }

    # Método para obtener planchas asociadas a una votación #
    public function obtenerPlanchasVotacion($id_votacion) {
        $id_votacion = $this->limpiarCadena($id_votacion);
        $consulta = $this->ejecutarConsulta("
            SELECT p.*, vp.ORDEN_PLANCHA, vp.FECHA_ASOCIACION
            FROM ugc_planchas p
            INNER JOIN ugc_votacion_planchas vp ON p.ID_PLANCHA = vp.ID_PLANCHA
            WHERE vp.ID_VOTACION = '$id_votacion'
            ORDER BY vp.ORDEN_PLANCHA ASC
        ");
        return $consulta->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>
