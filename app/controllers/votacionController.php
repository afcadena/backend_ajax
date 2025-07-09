<?php
namespace app\controllers;

require_once __DIR__ . '/../models/mainModel.php';

use app\models\mainModel;

class votacionController extends mainModel {
    
    # Controlador para crear votación #
public function crearVotacionControlador() {
    $id_tipo_solicitud     = $this->limpiarCadena($_POST['id_tipo_solicitud'] ?? null);
    $tipo_solicitud        = $this->limpiarCadena($_POST['tipo_solicitud']);
    $servicio              = $this->limpiarCadena($_POST['servicio'] ?? 'VOT');
    $fecha_inicio          = $this->limpiarCadena($_POST['fecha_inicio']);
    $fecha_fin             = $this->limpiarCadena($_POST['fecha_fin']);
    $id_tipo_dependiente   = $this->limpiarCadena($_POST['id_tipo_dependiente'] ?? null);
    $agrupador             = $this->limpiarCadena($_POST['agrupador']);
    $usuario_creador       = $this->limpiarCadena($_POST['usuario_creador'] ?? 'admin');

    // Validación de campos obligatorios
    if ($tipo_solicitud == "" || $fecha_inicio == "" || $fecha_fin == "" || $agrupador == "") {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Campos incompletos",
            "texto" => "Todos los campos obligatorios deben estar completos.",
            "icono" => "error"
        ]);
    }

    // Validación de fechas
    if (strtotime($fecha_inicio) >= strtotime($fecha_fin)) {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Fechas inválidas",
            "texto" => "La fecha de inicio debe ser anterior a la fecha de fin.",
            "icono" => "error"
        ]);
    }

    // Preparar datos para inserción
    $datos = [];

    if (!empty($id_tipo_solicitud)) {
        $datos[] = [
            "campo_nombre" => "ID_TIPO_SOLICITUD",
            "campo_marcador" => ":id",
            "campo_valor" => $id_tipo_solicitud
        ];
    }

    $datos[] = ["campo_nombre" => "TIPO_SOLICITUD", "campo_marcador" => ":tipo", "campo_valor" => $tipo_solicitud];
    $datos[] = ["campo_nombre" => "SERVICIO", "campo_marcador" => ":servicio", "campo_valor" => $servicio];
    $datos[] = ["campo_nombre" => "FECHA_INICIO", "campo_marcador" => ":inicio", "campo_valor" => $fecha_inicio];
    $datos[] = ["campo_nombre" => "FECHA_FIN", "campo_marcador" => ":fin", "campo_valor" => $fecha_fin];
    $datos[] = ["campo_nombre" => "AGRUPADOR", "campo_marcador" => ":agrupador", "campo_valor" => $agrupador];

    if (!empty($id_tipo_dependiente)) {
        $datos[] = [
            "campo_nombre" => "ID_TIPO_DEPENDIENTE",
            "campo_marcador" => ":dependiente",
            "campo_valor" => $id_tipo_dependiente
        ];
    }

    // Guardar en la base de datos
    $guardar = $this->guardarDatos("ugc_tipo_solicitud", $datos);

    if ($guardar->rowCount() >= 1) {
        // Obtener ID insertado (si no se envió manualmente)
        $id = !empty($id_tipo_solicitud) ? $id_tipo_solicitud : $this->conectar()->lastInsertId();

        return json_encode([
            "tipo" => "limpiar",
            "titulo" => "Votación registrada",
            "texto" => "La votación fue registrada correctamente.",
            "icono" => "success",
            "id_votacion" => $id
        ]);
    } else {
        return json_encode([
            "tipo" => "simple",
            "titulo" => "Error",
            "texto" => "No se pudo guardar la votación.",
            "icono" => "error"
        ]);
    }
}


    # Controlador para modificar votación #
    public function modificarVotacionControlador() {
        $id_votacion = $this->limpiarCadena($_POST['id_votacion']);
        $titulo = $this->limpiarCadena($_POST['titulo']);
        $id_tipo_votacion = $this->limpiarCadena($_POST['id_tipo_votacion']);
        $id_facultad = $this->limpiarCadena($_POST['id_facultad']);
        $fecha_inicio = $this->limpiarCadena($_POST['fecha_inicio']);
        $fecha_fin = $this->limpiarCadena($_POST['fecha_fin']);

        // Validar campos obligatorios
        if ($id_votacion == "" || $titulo == "" || $id_tipo_votacion == "" || $id_facultad == "" || $fecha_inicio == "" || $fecha_fin == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Campos incompletos",
                "texto" => "Todos los campos son obligatorios",
                "icono" => "error"
            ]);
        }

        // Verificar que la votación existe
        $verificar_votacion = $this->ejecutarConsulta("SELECT ESTADO FROM ugc_votaciones WHERE ID_VOTACION = '$id_votacion'");
        if ($verificar_votacion->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Votación no encontrada",
                "texto" => "La votación que intenta modificar no existe",
                "icono" => "error"
            ]);
        }

        $estado_votacion = $verificar_votacion->fetch(\PDO::FETCH_ASSOC);
        if ($estado_votacion['ESTADO'] == 'ACTIVA' || $estado_votacion['ESTADO'] == 'FINALIZADA') {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Modificación no permitida",
                "texto" => "No se puede modificar una votación activa o finalizada",
                "icono" => "error"
            ]);
        }

        // Validar fechas
        if (strtotime($fecha_inicio) >= strtotime($fecha_fin)) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error en fechas",
                "texto" => "La fecha de inicio debe ser anterior a la fecha de fin",
                "icono" => "error"
            ]);
        }

        $datos_actualizacion = [
            ["campo_nombre" => "TITULO", "campo_marcador" => ":Titulo", "campo_valor" => $titulo],
            ["campo_nombre" => "ID_TIPO_VOTACION", "campo_marcador" => ":TipoVotacion", "campo_valor" => $id_tipo_votacion],
            ["campo_nombre" => "ID_FACULTAD", "campo_marcador" => ":Facultad", "campo_valor" => $id_facultad],
            ["campo_nombre" => "FECHA_INICIO", "campo_marcador" => ":FechaInicio", "campo_valor" => $fecha_inicio],
            ["campo_nombre" => "FECHA_FIN", "campo_marcador" => ":FechaFin", "campo_valor" => $fecha_fin],
            ["campo_nombre" => "FECHA_MODIFICACION", "campo_marcador" => ":FechaModificacion", "campo_valor" => date("Y-m-d H:i:s")]
        ];

        $condicion = [
            "condicion_campo" => "ID_VOTACION",
            "condicion_marcador" => ":IdVotacion",
            "condicion_valor" => $id_votacion
        ];

        $actualizar_votacion = $this->actualizarDatos("ugc_votaciones", $datos_actualizacion, $condicion);

        if ($actualizar_votacion->rowCount() >= 1) {
            return json_encode([
                "tipo" => "recargar",
                "titulo" => "Votación modificada",
                "texto" => "La votación ha sido modificada correctamente",
                "icono" => "success"
            ]);
        } else {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Sin cambios",
                "texto" => "No se realizaron cambios en la votación",
                "icono" => "info"
            ]);
        }
    }

    # Controlador para eliminar votación #
    public function eliminarVotacionControlador() {
        $id_votacion = $this->limpiarCadena($_POST['id_votacion']);

        if ($id_votacion == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "ID de votación requerido",
                "icono" => "error"
            ]);
        }

        // Verificar que la votación existe y su estado
        $verificar_votacion = $this->ejecutarConsulta("SELECT ESTADO FROM ugc_votaciones WHERE ID_VOTACION = '$id_votacion'");
        if ($verificar_votacion->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Votación no encontrada",
                "texto" => "La votación que intenta eliminar no existe",
                "icono" => "error"
            ]);
        }

        $estado_votacion = $verificar_votacion->fetch(\PDO::FETCH_ASSOC);
        if ($estado_votacion['ESTADO'] == 'ACTIVA' || $estado_votacion['ESTADO'] == 'FINALIZADA') {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Eliminación no permitida",
                "texto" => "No se puede eliminar una votación activa o finalizada",
                "icono" => "error"
            ]);
        }

        $eliminar_votacion = $this->eliminarRegistro("ugc_votaciones", "ID_VOTACION", $id_votacion);

        if ($eliminar_votacion->rowCount() >= 1) {
            return json_encode([
                "tipo" => "recargar",
                "titulo" => "Votación eliminada",
                "texto" => "La votación ha sido eliminada correctamente",
                "icono" => "success"
            ]);
        } else {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error inesperado",
                "texto" => "No se pudo eliminar la votación",
                "icono" => "error"
            ]);
        }
    }

    # Método para listar votaciones #
    public function listarVotacionesControlador() {
    $consulta = $this->ejecutarConsulta("
        SELECT 
            ID_TIPO_SOLICITUD AS ID_VOTACION,
            TIPO_SOLICITUD AS TITULO,
            SERVICIO,
            FECHA_INICIO,
            FECHA_FIN,
            AGRUPADOR,
            'PENDIENTE' AS ESTADO,
            NULL AS total_planchas -- si no usas planchas, este campo puede ser null o 0
        FROM ugc_tipo_solicitud
        WHERE SERVICIO = 'VOT'
        ORDER BY FECHA_INICIO DESC
    ");
    return $consulta->fetchAll(\PDO::FETCH_ASSOC);
}

    # Método para obtener datos de votación específica #
   public function obtenerVotacionControlador($id) {
    $id = $this->limpiarCadena($id);
    $consulta = $this->ejecutarConsulta("
        SELECT 
            ID_TIPO_SOLICITUD AS ID_VOTACION,
            TIPO_SOLICITUD AS TITULO,
            FECHA_INICIO,
            FECHA_FIN,
            AGRUPADOR,
            'PENDIENTE' AS ESTADO
        FROM ugc_tipo_solicitud
        WHERE ID_TIPO_SOLICITUD = '$id'
    ");
    return $consulta->fetch(\PDO::FETCH_ASSOC);
}

# Método para obtener tipos de votación desde la tabla ugc_tipo_solicitud #
public function obtenerTiposVotacion() {
    $consulta = $this->ejecutarConsulta("
        SELECT ID_TIPO_SOLICITUD AS ID_TIPO_VOTACION, TIPO_SOLICITUD AS NOMBRE_TIPO 
        FROM ugc_tipo_solicitud 
        ORDER BY TIPO_SOLICITUD ASC
    ");
    return $consulta->fetchAll(\PDO::FETCH_ASSOC);
}


}
?>
