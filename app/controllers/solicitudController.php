<?php
namespace app\controllers;
use app\models\mainModel;

class solicitudController extends mainModel {
    
    # Controlador para crear solicitud #
    public function crearSolicitudControlador() {
        $destino = $this->limpiarCadena($_POST['destino']);
        $motivo = $this->limpiarCadena($_POST['motivo']);
        $id_tipo_solicitud = $this->limpiarCadena($_POST['id_tipo_solicitud']);
        $periodo_inicio = $this->limpiarCadena($_POST['periodo_inicio']);
        $periodo_fin = $this->limpiarCadena($_POST['periodo_fin']);
        $usuario = $this->limpiarCadena($_POST['usuario']);

        // Validar campos obligatorios
        if ($destino == "" || $motivo == "" || $id_tipo_solicitud == "" || $periodo_inicio == "" || $periodo_fin == "" || $usuario == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Campos incompletos",
                "texto" => "Todos los campos son obligatorios",
                "icono" => "error"
            ]);
        }

        // Validar fechas
        if (strtotime($periodo_inicio) >= strtotime($periodo_fin)) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error en fechas",
                "texto" => "La fecha de inicio debe ser anterior a la fecha de fin",
                "icono" => "error"
            ]);
        }

        $datos_solicitud = [
            ["campo_nombre" => "DESTINO", "campo_marcador" => ":Destino", "campo_valor" => $destino],
            ["campo_nombre" => "MOTIVO", "campo_marcador" => ":Motivo", "campo_valor" => $motivo],
            ["campo_nombre" => "ID_TIPO_SOLICITUD", "campo_marcador" => ":TipoSolicitud", "campo_valor" => $id_tipo_solicitud],
            ["campo_nombre" => "PERIODO_INICIO", "campo_marcador" => ":PeriodoInicio", "campo_valor" => $periodo_inicio],
            ["campo_nombre" => "PERIODO_FIN", "campo_marcador" => ":PeriodoFin", "campo_valor" => $periodo_fin],
            ["campo_nombre" => "USUARIO", "campo_marcador" => ":Usuario", "campo_valor" => $usuario]
        ];

        $insertar_solicitud = $this->guardarDatos("ugc_solicitud", $datos_solicitud);
        
        if ($insertar_solicitud->rowCount() >= 1) {
            // Obtener el ID de la solicitud insertada
            $id_solicitud = $this->conectar()->lastInsertId();
            
            return json_encode([
                "tipo" => "limpiar",
                "titulo" => "Solicitud creada",
                "texto" => "La solicitud ha sido creada correctamente",
                "icono" => "success",
                "id_solicitud" => $id_solicitud
            ]);
        } else {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error inesperado",
                "texto" => "No se pudo crear la solicitud",
                "icono" => "error"
            ]);
        }
    }

    # Controlador para modificar solicitud #
    public function modificarSolicitudControlador() {
        $id_solicitud = $this->limpiarCadena($_POST['id_solicitud']);
        $destino = $this->limpiarCadena($_POST['destino']);
        $motivo = $this->limpiarCadena($_POST['motivo']);
        $id_tipo_solicitud = $this->limpiarCadena($_POST['id_tipo_solicitud']);
        $periodo_inicio = $this->limpiarCadena($_POST['periodo_inicio']);
        $periodo_fin = $this->limpiarCadena($_POST['periodo_fin']);

        // Validar campos obligatorios
        if ($id_solicitud == "" || $destino == "" || $motivo == "" || $id_tipo_solicitud == "" || $periodo_inicio == "" || $periodo_fin == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Campos incompletos",
                "texto" => "Todos los campos son obligatorios",
                "icono" => "error"
            ]);
        }

        // Verificar que la solicitud existe
        $verificar_solicitud = $this->ejecutarConsulta("SELECT ESTADO FROM ugc_solicitud WHERE ID_SOLICITUD = '$id_solicitud'");
        if ($verificar_solicitud->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Solicitud no encontrada",
                "texto" => "La solicitud que intenta modificar no existe",
                "icono" => "error"
            ]);
        }

        $estado_solicitud = $verificar_solicitud->fetch(\PDO::FETCH_ASSOC);
        if ($estado_solicitud['ESTADO'] == 'APROBADA' || $estado_solicitud['ESTADO'] == 'RECHAZADA') {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Modificación no permitida",
                "texto" => "No se puede modificar una solicitud aprobada o rechazada",
                "icono" => "error"
            ]);
        }

        // Validar fechas
        if (strtotime($periodo_inicio) >= strtotime($periodo_fin)) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error en fechas",
                "texto" => "La fecha de inicio debe ser anterior a la fecha de fin",
                "icono" => "error"
            ]);
        }

        $datos_actualizacion = [
            ["campo_nombre" => "DESTINO", "campo_marcador" => ":Destino", "campo_valor" => $destino],
            ["campo_nombre" => "MOTIVO", "campo_marcador" => ":Motivo", "campo_valor" => $motivo],
            ["campo_nombre" => "ID_TIPO_SOLICITUD", "campo_marcador" => ":TipoSolicitud", "campo_valor" => $id_tipo_solicitud],
            ["campo_nombre" => "PERIODO_INICIO", "campo_marcador" => ":PeriodoInicio", "campo_valor" => $periodo_inicio],
            ["campo_nombre" => "PERIODO_FIN", "campo_marcador" => ":PeriodoFin", "campo_valor" => $periodo_fin],
            ["campo_nombre" => "FECHA_MODIFICACION", "campo_marcador" => ":FechaModificacion", "campo_valor" => date("Y-m-d H:i:s")]
        ];

        $condicion = [
            "condicion_campo" => "ID_SOLICITUD",
            "condicion_marcador" => ":IdSolicitud",
            "condicion_valor" => $id_solicitud
        ];

        $actualizar_solicitud = $this->actualizarDatos("ugc_solicitud", $datos_actualizacion, $condicion);

        if ($actualizar_solicitud->rowCount() >= 1) {
            return json_encode([
                "tipo" => "recargar",
                "titulo" => "Solicitud modificada",
                "texto" => "La solicitud ha sido modificada correctamente",
                "icono" => "success"
            ]);
        } else {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Sin cambios",
                "texto" => "No se realizaron cambios en la solicitud",
                "icono" => "info"
            ]);
        }
    }

    # Controlador para eliminar solicitud #
    public function eliminarSolicitudControlador() {
        $id_solicitud = $this->limpiarCadena($_POST['id_solicitud']);

        if ($id_solicitud == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "ID de solicitud requerido",
                "icono" => "error"
            ]);
        }

        // Verificar que la solicitud existe y su estado
        $verificar_solicitud = $this->ejecutarConsulta("SELECT ESTADO FROM ugc_solicitud WHERE ID_SOLICITUD = '$id_solicitud'");
        if ($verificar_solicitud->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Solicitud no encontrada",
                "texto" => "La solicitud que intenta eliminar no existe",
                "icono" => "error"
            ]);
        }

        $estado_solicitud = $verificar_solicitud->fetch(\PDO::FETCH_ASSOC);
        if ($estado_solicitud['ESTADO'] == 'APROBADA' || $estado_solicitud['ESTADO'] == 'RECHAZADA') {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Eliminación no permitida",
                "texto" => "No se puede eliminar una solicitud aprobada o rechazada",
                "icono" => "error"
            ]);
        }

        $eliminar_solicitud = $this->eliminarRegistro("ugc_solicitud", "ID_SOLICITUD", $id_solicitud);

        if ($eliminar_solicitud->rowCount() >= 1) {
            return json_encode([
                "tipo" => "recargar",
                "titulo" => "Solicitud eliminada",
                "texto" => "La solicitud ha sido eliminada correctamente",
                "icono" => "success"
            ]);
        } else {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error inesperado",
                "texto" => "No se pudo eliminar la solicitud",
                "icono" => "error"
            ]);
        }
    }

    # Método para listar solicitudes #
    public function listarSolicitudesControlador() {
        $consulta = $this->ejecutarConsulta("
            SELECT s.*, 
                   ts.TIPO_SOLICITUD
            FROM ugc_solicitud s
            INNER JOIN ugc_tipo_solicitud ts ON s.ID_TIPO_SOLICITUD = ts.ID_TIPO_SOLICITUD
            ORDER BY s.FECHA_CREACION DESC
        ");
        return $consulta->fetchAll(\PDO::FETCH_ASSOC);
    }

    # Método para obtener datos de solicitud específica #
    public function obtenerSolicitudControlador($id_solicitud) {
        $id_solicitud = $this->limpiarCadena($id_solicitud);
        $consulta = $this->ejecutarConsulta("
            SELECT s.*, 
                   ts.TIPO_SOLICITUD
            FROM ugc_solicitud s
            INNER JOIN ugc_tipo_solicitud ts ON s.ID_TIPO_SOLICITUD = ts.ID_TIPO_SOLICITUD
            WHERE s.ID_SOLICITUD = '$id_solicitud'
        ");
        return $consulta->fetch(\PDO::FETCH_ASSOC);
    }

    # Método para obtener tipos de solicitud #
    public function obtenerTiposSolicitud() {
        $consulta = $this->ejecutarConsulta("SELECT * FROM ugc_tipo_solicitud WHERE ESTADO = 'ACTIVO' ORDER BY TIPO_SOLICITUD ASC");
        return $consulta->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>
