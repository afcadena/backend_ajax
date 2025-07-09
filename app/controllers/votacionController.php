<?php
namespace app\controllers;
require_once __DIR__ . '/../models/mainModel.php';
use app\models\mainModel;

class votacionController extends mainModel {
    
    # Controlador para crear votación #
    public function crearVotacionControlador() {
        // Obtener datos del formulario
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
                "texto" => "Los campos TIPO_SOLICITUD, FECHA_INICIO, FECHA_FIN y AGRUPADOR son obligatorios.",
                "icono" => "error"
            ]);
        }

        // Validación de fechas
        if (strtotime($fecha_inicio) > strtotime($fecha_fin)) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Fechas inválidas",
                "texto" => "La fecha de inicio debe ser anterior o igual a la fecha de fin.",
                "icono" => "error"
            ]);
        }

        // Validar formato del agrupador (debe ser letra + 3 caracteres)
        if (!preg_match('/^[EDAX][A-Z]{3}$/', $agrupador)) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Agrupador inválido",
                "texto" => "El agrupador debe tener el formato correcto (ej: EDER, DING, AECO, XGEN).",
                "icono" => "error"
            ]);
        }

        // Verificar que no exista un agrupador duplicado para el mismo servicio
        $verificar_agrupador = $this->ejecutarConsulta("
            SELECT ID_TIPO_SOLICITUD 
            FROM ugc_tipo_solicitud 
            WHERE AGRUPADOR = '$agrupador' AND SERVICIO = '$servicio'
        ");
        
        if ($verificar_agrupador->rowCount() > 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Agrupador duplicado",
                "texto" => "Ya existe una votación con el agrupador '$agrupador' para este servicio.",
                "icono" => "error"
            ]);
        }

        // Preparar datos para inserción según la estructura exacta de la BD
        $datos = [];
        
        // Solo agregar ID_TIPO_SOLICITUD si se proporciona
        if (!empty($id_tipo_solicitud)) {
            $datos[] = [
                "campo_nombre" => "ID_TIPO_SOLICITUD",
                "campo_marcador" => ":id_tipo_solicitud",
                "campo_valor" => $id_tipo_solicitud
            ];
        }
        
        // Campos obligatorios
        $datos[] = ["campo_nombre" => "TIPO_SOLICITUD", "campo_marcador" => ":tipo_solicitud", "campo_valor" => $tipo_solicitud];
        $datos[] = ["campo_nombre" => "SERVICIO", "campo_marcador" => ":servicio", "campo_valor" => $servicio];
        $datos[] = ["campo_nombre" => "FECHA_INICIO", "campo_marcador" => ":fecha_inicio", "campo_valor" => $fecha_inicio];
        $datos[] = ["campo_nombre" => "FECHA_FIN", "campo_marcador" => ":fecha_fin", "campo_valor" => $fecha_fin];
        $datos[] = ["campo_nombre" => "AGRUPADOR", "campo_marcador" => ":agrupador", "campo_valor" => $agrupador];
        
        // Solo agregar ID_TIPO_DEPENDIENTE si se proporciona (puede ser null)
        if (!empty($id_tipo_dependiente)) {
            $datos[] = [
                "campo_nombre" => "ID_TIPO_DEPENDIENTE",
                "campo_marcador" => ":id_tipo_dependiente",
                "campo_valor" => $id_tipo_dependiente
            ];
        }

        // Guardar en la base de datos
        $guardar = $this->guardarDatos("ugc_tipo_solicitud", $datos);
        
        if ($guardar->rowCount() >= 1) {
            // Obtener ID insertado (si no se envió manualmente)
            $id = !empty($id_tipo_solicitud) ? $id_tipo_solicitud : $this->conectar()->lastInsertId();
            
            // Log de la operación
            error_log("Votación creada exitosamente - ID: $id, Agrupador: $agrupador, Usuario: $usuario_creador");
            
            return json_encode([
                "tipo" => "limpiar",
                "titulo" => "Votación registrada",
                "texto" => "La votación fue registrada correctamente con agrupador '$agrupador'.",
                "icono" => "success",
                "id_votacion" => $id
            ]);
        } else {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "No se pudo guardar la votación. Verifique los datos e intente nuevamente.",
                "icono" => "error"
            ]);
        }
    }

    # Controlador para modificar votación #
    public function modificarVotacionControlador() {
        $id_tipo_solicitud = $this->limpiarCadena($_POST['id_tipo_solicitud']);
        $tipo_solicitud = $this->limpiarCadena($_POST['tipo_solicitud']);
        $fecha_inicio = $this->limpiarCadena($_POST['fecha_inicio']);
        $fecha_fin = $this->limpiarCadena($_POST['fecha_fin']);
        $id_tipo_dependiente = $this->limpiarCadena($_POST['id_tipo_dependiente'] ?? null);
        $agrupador = $this->limpiarCadena($_POST['agrupador']);

        // Validar campos obligatorios
        if ($id_tipo_solicitud == "" || $tipo_solicitud == "" || $fecha_inicio == "" || $fecha_fin == "" || $agrupador == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Campos incompletos",
                "texto" => "Todos los campos obligatorios deben estar completos",
                "icono" => "error"
            ]);
        }

        // Verificar que la votación existe
        $verificar_votacion = $this->ejecutarConsulta("SELECT ID_TIPO_SOLICITUD FROM ugc_tipo_solicitud WHERE ID_TIPO_SOLICITUD = '$id_tipo_solicitud'");
        if ($verificar_votacion->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Votación no encontrada",
                "texto" => "La votación que intenta modificar no existe",
                "icono" => "error"
            ]);
        }

        // Validar fechas
        if (strtotime($fecha_inicio) > strtotime($fecha_fin)) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error en fechas",
                "texto" => "La fecha de inicio debe ser anterior o igual a la fecha de fin",
                "icono" => "error"
            ]);
        }

        $datos_actualizacion = [
            ["campo_nombre" => "TIPO_SOLICITUD", "campo_marcador" => ":tipo_solicitud", "campo_valor" => $tipo_solicitud],
            ["campo_nombre" => "FECHA_INICIO", "campo_marcador" => ":fecha_inicio", "campo_valor" => $fecha_inicio],
            ["campo_nombre" => "FECHA_FIN", "campo_marcador" => ":fecha_fin", "campo_valor" => $fecha_fin],
            ["campo_nombre" => "AGRUPADOR", "campo_marcador" => ":agrupador", "campo_valor" => $agrupador]
        ];

        if (!empty($id_tipo_dependiente)) {
            $datos_actualizacion[] = ["campo_nombre" => "ID_TIPO_DEPENDIENTE", "campo_marcador" => ":id_tipo_dependiente", "campo_valor" => $id_tipo_dependiente];
        }

        $condicion = [
            "condicion_campo" => "ID_TIPO_SOLICITUD",
            "condicion_marcador" => ":id_tipo_solicitud",
            "condicion_valor" => $id_tipo_solicitud
        ];

        $actualizar_votacion = $this->actualizarDatos("ugc_tipo_solicitud", $datos_actualizacion, $condicion);

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
        $id_tipo_solicitud = $this->limpiarCadena($_POST['id_tipo_solicitud']);

        if ($id_tipo_solicitud == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "ID de votación requerido",
                "icono" => "error"
            ]);
        }

        // Verificar que la votación existe
        $verificar_votacion = $this->ejecutarConsulta("SELECT ID_TIPO_SOLICITUD FROM ugc_tipo_solicitud WHERE ID_TIPO_SOLICITUD = '$id_tipo_solicitud' AND SERVICIO = 'VOT'");
        if ($verificar_votacion->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Votación no encontrada",
                "texto" => "La votación que intenta eliminar no existe",
                "icono" => "error"
            ]);
        }

        $eliminar_votacion = $this->eliminarRegistro("ugc_tipo_solicitud", "ID_TIPO_SOLICITUD", $id_tipo_solicitud);

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
                ID_TIPO_SOLICITUD,
                TIPO_SOLICITUD,
                SERVICIO,
                FECHA_INICIO,
                FECHA_FIN,
                ID_TIPO_DEPENDIENTE,
                AGRUPADOR
            FROM ugc_tipo_solicitud
            WHERE SERVICIO = 'VOT'
            ORDER BY FECHA_INICIO DESC
        ");
        
        $votaciones = $consulta->fetchAll(\PDO::FETCH_ASSOC);
        
        // Procesar cada votación para extraer información del agrupador
        foreach ($votaciones as &$votacion) {
            $agrupador = $votacion['AGRUPADOR'];
            
            // Extraer tipo de dependiente del primer carácter
            $letra_dependiente = substr($agrupador, 0, 1);
            switch ($letra_dependiente) {
                case 'E':
                    $votacion['TIPO_DEPENDIENTE'] = 'Estudiante';
                    break;
                case 'D':
                    $votacion['TIPO_DEPENDIENTE'] = 'Docente';
                    break;
                case 'A':
                    $votacion['TIPO_DEPENDIENTE'] = 'Administrativo';
                    break;
                default:
                    $votacion['TIPO_DEPENDIENTE'] = 'Sin asignar';
            }
            
            // Extraer facultad de los últimos 3 caracteres
            $codigo_facultad = substr($agrupador, 1);
            $facultades_map = [
                'DER' => 'Derecho',
                'ING' => 'Ingeniería',
                'ECO' => 'Economía',
                'SAL' => 'Salud',
                'MED' => 'Medicina',
                'EDU' => 'Educación',
                'ADM' => 'Administración',
                'CON' => 'Consiliatura/Contaduría',
                'CIE' => 'Ciencias',
                'SOC' => 'Ciencias Sociales',
                'GEN' => 'General'
            ];
            
            $votacion['FACULTAD'] = $facultades_map[$codigo_facultad] ?? 'Facultad no identificada';
            
            // Agregar estado por defecto
            $votacion['ESTADO'] = 'PENDIENTE';
        }
        
        return $votaciones;
    }

    # Método para obtener datos de votación específica #
    public function obtenerVotacionControlador($id) {
        $id = $this->limpiarCadena($id);
        $consulta = $this->ejecutarConsulta("
            SELECT 
                ID_TIPO_SOLICITUD,
                TIPO_SOLICITUD,
                SERVICIO,
                FECHA_INICIO,
                FECHA_FIN,
                ID_TIPO_DEPENDIENTE,
                AGRUPADOR
            FROM ugc_tipo_solicitud
            WHERE ID_TIPO_SOLICITUD = '$id'
        ");
        
        $votacion = $consulta->fetch(\PDO::FETCH_ASSOC);
        
        if ($votacion) {
            $agrupador = $votacion['AGRUPADOR'];
            
            // Extraer tipo de dependiente
            $letra_dependiente = substr($agrupador, 0, 1);
            switch ($letra_dependiente) {
                case 'E':
                    $votacion['TIPO_DEPENDIENTE'] = 'Estudiante';
                    break;
                case 'D':
                    $votacion['TIPO_DEPENDIENTE'] = 'Docente';
                    break;
                case 'A':
                    $votacion['TIPO_DEPENDIENTE'] = 'Administrativo';
                    break;
                default:
                    $votacion['TIPO_DEPENDIENTE'] = 'Sin asignar';
            }
            
            // Extraer facultad
            $codigo_facultad = substr($agrupador, 1);
            $facultades_map = [
                'DER' => 'Derecho',
                'ING' => 'Ingeniería',
                'ECO' => 'Economía',
                'SAL' => 'Salud',
                'MED' => 'Medicina',
                'EDU' => 'Educación',
                'ADM' => 'Administración',
                'CON' => 'Consiliatura/Contaduría',
                'CIE' => 'Ciencias',
                'SOC' => 'Ciencias Sociales',
                'GEN' => 'General'
            ];
            
            $votacion['FACULTAD'] = $facultades_map[$codigo_facultad] ?? 'Facultad no identificada';
            $votacion['ESTADO'] = 'PENDIENTE';
        }
        
        return $votacion;
    }

    # Método para obtener tipos de votación desde la tabla ugc_tipo_solicitud #
    public function obtenerTiposVotacion() {
        $consulta = $this->ejecutarConsulta("
            SELECT 
                ID_TIPO_SOLICITUD, 
                TIPO_SOLICITUD,
                AGRUPADOR,
                FECHA_INICIO,
                FECHA_FIN
            FROM ugc_tipo_solicitud 
            WHERE SERVICIO = 'VOT'
            ORDER BY TIPO_SOLICITUD ASC
        ");
        return $consulta->fetchAll(\PDO::FETCH_ASSOC);
    }

    # Método para obtener estadísticas de agrupadores #
    public function obtenerEstadisticasAgrupadores() {
        $consulta = $this->ejecutarConsulta("
            SELECT 
                AGRUPADOR,
                COUNT(*) as total_votaciones,
                MIN(FECHA_INICIO) as primera_votacion,
                MAX(FECHA_FIN) as ultima_votacion
            FROM ugc_tipo_solicitud
            WHERE SERVICIO = 'VOT'
            GROUP BY AGRUPADOR
            ORDER BY total_votaciones DESC
        ");
        return $consulta->fetchAll(\PDO::FETCH_ASSOC);
    }

    # Método para validar agrupador único #
    public function validarAgrupadorUnico($agrupador, $servicio = 'VOT', $excluir_id = null) {
        $condicion_exclusion = $excluir_id ? "AND ID_TIPO_SOLICITUD != '$excluir_id'" : "";
        
        $consulta = $this->ejecutarConsulta("
            SELECT COUNT(*) as total
            FROM ugc_tipo_solicitud 
            WHERE AGRUPADOR = '$agrupador' 
            AND SERVICIO = '$servicio'
            $condicion_exclusion
        ");
        
        $resultado = $consulta->fetch(\PDO::FETCH_ASSOC);
        return $resultado['total'] == 0;
    }
}
?>
