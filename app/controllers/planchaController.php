<?php
namespace app\controllers;
require_once __DIR__ . '/../models/mainModel.php';
use app\models\mainModel;

class planchaController extends mainModel {
    
    # Controlador para crear plancha con imagen siguiendo el flujo establecido #
    public function crearPlanchaControlador() {
        $opcion = $this->limpiarCadena($_POST['opcion']);
        $id_pregunta = $this->limpiarCadena($_POST['id_pregunta']);
        $id_tipo_solicitud = $this->limpiarCadena($_POST['id_tipo_solicitud']);
        $url = isset($_POST['url']) ? $this->limpiarCadena($_POST['url']) : '';

        // Validar campos obligatorios
        if ($opcion == "" || $id_pregunta == "" || $id_tipo_solicitud == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Campos incompletos",
                "texto" => "El nombre de la plancha, pregunta y tipo de votación son obligatorios",
                "icono" => "error"
            ]);
        }

        // Verificar que la pregunta existe y está activa
        $verificar_pregunta = $this->ejecutarConsulta("
            SELECT ID_PREGUNTA, PREGUNTA 
            FROM ugc_preguntas 
            WHERE ID_PREGUNTA = '$id_pregunta' AND ESTADO = 'ACTIVO'
        ");
        
        if ($verificar_pregunta->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Pregunta no encontrada",
                "texto" => "La pregunta seleccionada no existe o está inactiva",
                "icono" => "error"
            ]);
        }

        // Verificar que el tipo de solicitud existe y está activo
        $verificar_tipo = $this->ejecutarConsulta("
            SELECT ID_TIPO_SOLICITUD, TIPO_SOLICITUD, AGRUPADOR 
            FROM ugc_tipo_solicitud 
            WHERE ID_TIPO_SOLICITUD = '$id_tipo_solicitud' AND ESTADO = 'ACTIVO' AND SERVICIO = 'VOT'
        ");
        
        if ($verificar_tipo->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Tipo de votación no encontrado",
                "texto" => "El tipo de votación seleccionado no existe o está inactivo",
                "icono" => "error"
            ]);
        }

        $datos_tipo = $verificar_tipo->fetch(\PDO::FETCH_ASSOC);

        // Buscar o crear la relación pregunta-tipo de solicitud
        $solicitud_pregunta = $this->ejecutarConsulta("
            SELECT ID_SOLICITUD_PREGUNTA 
            FROM ugc_solicitud_pregunta 
            WHERE ID_PREGUNTA = '$id_pregunta' AND ID_TIPO_SOLICITUD = '$id_tipo_solicitud'
        ");

        if ($solicitud_pregunta->rowCount() == 0) {
            // Crear la relación automáticamente siguiendo el flujo
            $datos_solicitud = [
                ["campo_nombre" => "ID_PREGUNTA", "campo_marcador" => ":IdPregunta", "campo_valor" => $id_pregunta],
                ["campo_nombre" => "ID_TIPO_SOLICITUD", "campo_marcador" => ":IdTipoSolicitud", "campo_valor" => $id_tipo_solicitud]
            ];

            $insertar_solicitud = $this->guardarDatos("ugc_solicitud_pregunta", $datos_solicitud);
            
            if ($insertar_solicitud->rowCount() >= 1) {
                $id_solicitud_pregunta = $this->conexion->lastInsertId();
            } else {
                return json_encode([
                    "tipo" => "simple",
                    "titulo" => "Error en relación",
                    "texto" => "No se pudo crear la relación pregunta-votación",
                    "icono" => "error"
                ]);
            }
        } else {
            $datos_solicitud = $solicitud_pregunta->fetch(\PDO::FETCH_ASSOC);
            $id_solicitud_pregunta = $datos_solicitud['ID_SOLICITUD_PREGUNTA'];
        }

        // Verificar que no exista una plancha con el mismo nombre para la misma pregunta
        $verificar_opcion = $this->ejecutarConsulta("
            SELECT ID_OPCION_PREGUNTA 
            FROM ugc_opcion_pregunta 
            WHERE OPCION = '$opcion' AND ID_SOLICITUD_PREGUNTA = '$id_solicitud_pregunta'
        ");
        
        if ($verificar_opcion->rowCount() > 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Plancha duplicada",
                "texto" => "Ya existe una plancha con este nombre para esta pregunta y tipo de votación",
                "icono" => "error"
            ]);
        }

        // Manejar carga de imagen siguiendo el esquema de nombres
        $ruta_imagen = '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $resultado_upload = $this->subirImagen($_FILES['imagen'], $datos_tipo['AGRUPADOR'], $opcion);
            if ($resultado_upload['success']) {
                $ruta_imagen = $resultado_upload['ruta'];
            } else {
                return json_encode([
                    "tipo" => "simple",
                    "titulo" => "Error en imagen",
                    "texto" => $resultado_upload['mensaje'],
                    "icono" => "error"
                ]);
            }
        }

        // Validar URL si se proporciona y no hay imagen subida
        if (!empty($url) && empty($ruta_imagen)) {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return json_encode([
                    "tipo" => "simple",
                    "titulo" => "URL inválida",
                    "texto" => "La URL proporcionada no es válida",
                    "icono" => "error"
                ]);
            }
        }

        // Crear la plancha con toda la información
        $datos_opcion = [
            ["campo_nombre" => "ID_SOLICITUD_PREGUNTA", "campo_marcador" => ":IdSolicitudPregunta", "campo_valor" => $id_solicitud_pregunta],
            ["campo_nombre" => "OPCION", "campo_marcador" => ":Opcion", "campo_valor" => $opcion],
            ["campo_nombre" => "URL", "campo_marcador" => ":Url", "campo_valor" => $url],
            ["campo_nombre" => "RUTA_IMAGEN", "campo_marcador" => ":RutaImagen", "campo_valor" => $ruta_imagen]
        ];

        $insertar_opcion = $this->guardarDatos("ugc_opcion_pregunta", $datos_opcion);
        
        if ($insertar_opcion->rowCount() >= 1) {
            return json_encode([
                "tipo" => "limpiar",
                "titulo" => "Plancha creada exitosamente",
                "texto" => "La plancha ha sido creada y asociada correctamente con la votación",
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

    # Método para subir imagen con esquema de nombres específico #
    private function subirImagen($archivo, $agrupador, $nombre_plancha) {
        $directorio_destino = __DIR__ . "/../../uploads/planchas/";
        
        // Crear directorio principal si no existe
        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }

        // Crear subdirectorio por agrupador (facultad)
        $directorio_agrupador = $directorio_destino . strtolower($agrupador) . "/";
        if (!file_exists($directorio_agrupador)) {
            mkdir($directorio_agrupador, 0777, true);
        }

        // Validar tipo de archivo
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($archivo['type'], $tipos_permitidos)) {
            return [
                'success' => false,
                'mensaje' => 'Tipo de archivo no permitido. Solo se permiten JPG, PNG, GIF y WebP'
            ];
        }

        // Validar tamaño (máximo 5MB)
        if ($archivo['size'] > 5 * 1024 * 1024) {
            return [
                'success' => false,
                'mensaje' => 'El archivo es demasiado grande. Máximo 5MB'
            ];
        }

        // Generar nombre siguiendo el esquema: plancha_nombreplancha_agrupador_timestamp
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombre_limpio = preg_replace('/[^a-zA-Z0-9]/', '_', strtolower($nombre_plancha));
        $agrupador_limpio = strtolower($agrupador);
        $timestamp = time();
        $nombre_archivo = "plancha_{$nombre_limpio}_{$agrupador_limpio}_{$timestamp}.{$extension}";
        
        $ruta_completa = $directorio_agrupador . $nombre_archivo;
        $ruta_relativa = "uploads/planchas/{$agrupador_limpio}/{$nombre_archivo}";

        // Mover archivo
        if (move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
            return [
                'success' => true,
                'ruta' => $ruta_relativa
            ];
        } else {
            return [
                'success' => false,
                'mensaje' => 'Error al subir el archivo al servidor'
            ];
        }
    }

    # Controlador para modificar plancha #
    public function modificarPlanchaControlador() {
        $id_opcion_pregunta = $this->limpiarCadena($_POST['id_opcion_pregunta']);
        $opcion = $this->limpiarCadena($_POST['opcion']);
        $url = isset($_POST['url']) ? $this->limpiarCadena($_POST['url']) : '';

        // Validar campos obligatorios
        if ($id_opcion_pregunta == "" || $opcion == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Campos incompletos",
                "texto" => "ID y nombre de la plancha son obligatorios",
                "icono" => "error"
            ]);
        }

        // Obtener datos actuales de la plancha
        $verificar_opcion = $this->ejecutarConsulta("
            SELECT op.*, ts.AGRUPADOR 
            FROM ugc_opcion_pregunta op
            INNER JOIN ugc_solicitud_pregunta sp ON op.ID_SOLICITUD_PREGUNTA = sp.ID_SOLICITUD_PREGUNTA
            INNER JOIN ugc_tipo_solicitud ts ON sp.ID_TIPO_SOLICITUD = ts.ID_TIPO_SOLICITUD
            WHERE op.ID_OPCION_PREGUNTA = '$id_opcion_pregunta'
        ");
        
        if ($verificar_opcion->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Plancha no encontrada",
                "texto" => "La plancha que intenta modificar no existe",
                "icono" => "error"
            ]);
        }

        $datos_actuales = $verificar_opcion->fetch(\PDO::FETCH_ASSOC);
        $ruta_imagen_actual = $datos_actuales['RUTA_IMAGEN'];
        $agrupador = $datos_actuales['AGRUPADOR'];

        // Manejar nueva imagen si se sube
        $nueva_ruta_imagen = $ruta_imagen_actual;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $resultado_upload = $this->subirImagen($_FILES['imagen'], $agrupador, $opcion);
            if ($resultado_upload['success']) {
                // Eliminar imagen anterior si existe
                if (!empty($ruta_imagen_actual) && file_exists(__DIR__ . "/../../" . $ruta_imagen_actual)) {
                    unlink(__DIR__ . "/../../" . $ruta_imagen_actual);
                }
                $nueva_ruta_imagen = $resultado_upload['ruta'];
            } else {
                return json_encode([
                    "tipo" => "simple",
                    "titulo" => "Error en imagen",
                    "texto" => $resultado_upload['mensaje'],
                    "icono" => "error"
                ]);
            }
        }

        // Validar URL si se proporciona
        if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "URL inválida",
                "texto" => "La URL proporcionada no es válida",
                "icono" => "error"
            ]);
        }

        $datos_actualizacion = [
            ["campo_nombre" => "OPCION", "campo_marcador" => ":Opcion", "campo_valor" => $opcion],
            ["campo_nombre" => "URL", "campo_marcador" => ":Url", "campo_valor" => $url],
            ["campo_nombre" => "RUTA_IMAGEN", "campo_marcador" => ":RutaImagen", "campo_valor" => $nueva_ruta_imagen],
            ["campo_nombre" => "FECHA_MODIFICACION", "campo_marcador" => ":FechaModificacion", "campo_valor" => date("Y-m-d H:i:s")]
        ];

        $condicion = [
            "condicion_campo" => "ID_OPCION_PREGUNTA",
            "condicion_marcador" => ":IdOpcionPregunta",
            "condicion_valor" => $id_opcion_pregunta
        ];

        $actualizar_opcion = $this->actualizarDatos("ugc_opcion_pregunta", $datos_actualizacion, $condicion);

        if ($actualizar_opcion->rowCount() >= 1) {
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
        $id_opcion_pregunta = $this->limpiarCadena($_POST['id_opcion_pregunta']);

        if ($id_opcion_pregunta == "") {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "ID de plancha requerido",
                "icono" => "error"
            ]);
        }

        // Verificar que la plancha existe y obtener ruta de imagen
        $verificar_opcion = $this->ejecutarConsulta("
            SELECT ID_OPCION_PREGUNTA, RUTA_IMAGEN, OPCION
            FROM ugc_opcion_pregunta 
            WHERE ID_OPCION_PREGUNTA = '$id_opcion_pregunta'
        ");
        
        if ($verificar_opcion->rowCount() == 0) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Plancha no encontrada",
                "texto" => "La plancha que intenta eliminar no existe",
                "icono" => "error"
            ]);
        }

        $datos_plancha = $verificar_opcion->fetch(\PDO::FETCH_ASSOC);
        
        $eliminar_opcion = $this->eliminarRegistro("ugc_opcion_pregunta", "ID_OPCION_PREGUNTA", $id_opcion_pregunta);

        if ($eliminar_opcion->rowCount() >= 1) {
            // Eliminar imagen física si existe
            if (!empty($datos_plancha['RUTA_IMAGEN']) && file_exists(__DIR__ . "/../../" . $datos_plancha['RUTA_IMAGEN'])) {
                unlink(__DIR__ . "/../../" . $datos_plancha['RUTA_IMAGEN']);
            }
            
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Plancha eliminada",
                "texto" => "La plancha '{$datos_plancha['OPCION']}' ha sido eliminada correctamente",
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

    # Método para obtener todas las preguntas disponibles #
    public function obtenerPreguntasDisponibles() {
        try {
            $consulta = $this->ejecutarConsulta("
                SELECT 
                    ID_PREGUNTA,
                    PREGUNTA,
                    ESTADO,
                    FECHA_CREACION
                FROM ugc_preguntas 
                WHERE ESTADO = 'ACTIVO'
                ORDER BY ID_PREGUNTA ASC
            ");
            return $consulta->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    # Método para obtener tipos de solicitud por servicio #
    public function obtenerTiposSolicitud() {
        try {
            $consulta = $this->ejecutarConsulta("
                SELECT 
                    ID_TIPO_SOLICITUD,
                    TIPO_SOLICITUD,
                    AGRUPADOR,
                    DESCRIPCION,
                    SERVICIO
                FROM ugc_tipo_solicitud 
                WHERE SERVICIO = 'VOT' AND ESTADO = 'ACTIVO'
                ORDER BY AGRUPADOR ASC, TIPO_SOLICITUD ASC
            ");
            return $consulta->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    # Método para listar planchas con información completa #
    public function listarPlanchasControlador() {
        try {
            $consulta = $this->ejecutarConsulta("
                SELECT 
                    op.ID_OPCION_PREGUNTA,
                    op.OPCION,
                    op.URL,
                    op.RUTA_IMAGEN,
                    op.FECHA_CREACION,
                    op.FECHA_MODIFICACION,
                    op.ESTADO,
                    p.ID_PREGUNTA,
                    p.PREGUNTA,
                    ts.ID_TIPO_SOLICITUD,
                    ts.TIPO_SOLICITUD,
                    ts.AGRUPADOR,
                    ts.DESCRIPCION as DESCRIPCION_TIPO,
                    sp.ID_SOLICITUD_PREGUNTA
                FROM ugc_opcion_pregunta op
                INNER JOIN ugc_solicitud_pregunta sp ON op.ID_SOLICITUD_PREGUNTA = sp.ID_SOLICITUD_PREGUNTA
                INNER JOIN ugc_preguntas p ON sp.ID_PREGUNTA = p.ID_PREGUNTA
                INNER JOIN ugc_tipo_solicitud ts ON sp.ID_TIPO_SOLICITUD = ts.ID_TIPO_SOLICITUD
                WHERE ts.SERVICIO = 'VOT' AND op.ESTADO = 'ACTIVO'
                ORDER BY ts.AGRUPADOR ASC, p.ID_PREGUNTA ASC, op.OPCION ASC
            ");
            return $consulta->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    # Método para obtener datos completos de una plancha específica #
    public function obtenerPlanchaControlador($id_opcion_pregunta) {
        $id_opcion_pregunta = $this->limpiarCadena($id_opcion_pregunta);
        try {
            $consulta = $this->ejecutarConsulta("
                SELECT 
                    op.*,
                    p.ID_PREGUNTA,
                    p.PREGUNTA,
                    ts.ID_TIPO_SOLICITUD,
                    ts.TIPO_SOLICITUD,
                    ts.AGRUPADOR,
                    ts.DESCRIPCION as DESCRIPCION_TIPO,
                    sp.ID_SOLICITUD_PREGUNTA
                FROM ugc_opcion_pregunta op
                INNER JOIN ugc_solicitud_pregunta sp ON op.ID_SOLICITUD_PREGUNTA = sp.ID_SOLICITUD_PREGUNTA
                INNER JOIN ugc_preguntas p ON sp.ID_PREGUNTA = p.ID_PREGUNTA
                INNER JOIN ugc_tipo_solicitud ts ON sp.ID_TIPO_SOLICITUD = ts.ID_TIPO_SOLICITUD
                WHERE op.ID_OPCION_PREGUNTA = '$id_opcion_pregunta'
            ");
            return $consulta->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return false;
        }
    }

    # Método para obtener estadísticas completas del sistema #
    public function obtenerEstadisticasPlanchas() {
        try {
            // Estadísticas de planchas
            $consulta_planchas = $this->ejecutarConsulta("
                SELECT 
                    COUNT(*) as total_planchas,
                    COUNT(CASE WHEN (URL IS NOT NULL AND URL != '') OR (RUTA_IMAGEN IS NOT NULL AND RUTA_IMAGEN != '') THEN 1 END) as con_imagen,
                    COUNT(CASE WHEN RUTA_IMAGEN IS NOT NULL AND RUTA_IMAGEN != '' THEN 1 END) as con_archivo_local,
                    COUNT(CASE WHEN URL IS NOT NULL AND URL != '' AND (RUTA_IMAGEN IS NULL OR RUTA_IMAGEN = '') THEN 1 END) as solo_url,
                    COUNT(DISTINCT sp.ID_TIPO_SOLICITUD) as tipos_votacion_utilizados,
                    COUNT(DISTINCT p.ID_PREGUNTA) as preguntas_utilizadas
                FROM ugc_opcion_pregunta op
                INNER JOIN ugc_solicitud_pregunta sp ON op.ID_SOLICITUD_PREGUNTA = sp.ID_SOLICITUD_PREGUNTA
                INNER JOIN ugc_preguntas p ON sp.ID_PREGUNTA = p.ID_PREGUNTA
                INNER JOIN ugc_tipo_solicitud ts ON sp.ID_TIPO_SOLICITUD = ts.ID_TIPO_SOLICITUD
                WHERE ts.SERVICIO = 'VOT' AND op.ESTADO = 'ACTIVO'
            ");
            $stats_planchas = $consulta_planchas->fetch(\PDO::FETCH_ASSOC);
            
            // Estadísticas de preguntas disponibles
            $consulta_preguntas = $this->ejecutarConsulta("
                SELECT COUNT(*) as total_preguntas_disponibles
                FROM ugc_preguntas 
                WHERE ESTADO = 'ACTIVO'
            ");
            $stats_preguntas = $consulta_preguntas->fetch(\PDO::FETCH_ASSOC);
            
            // Estadísticas de tipos de solicitud
            $consulta_tipos = $this->ejecutarConsulta("
                SELECT COUNT(*) as total_tipos_votacion
                FROM ugc_tipo_solicitud 
                WHERE SERVICIO = 'VOT' AND ESTADO = 'ACTIVO'
            ");
            $stats_tipos = $consulta_tipos->fetch(\PDO::FETCH_ASSOC);
            
            return array_merge($stats_planchas, $stats_preguntas, $stats_tipos);
        } catch (\Exception $e) {
            return [
                'total_planchas' => 0,
                'con_imagen' => 0,
                'con_archivo_local' => 0,
                'solo_url' => 0,
                'tipos_votacion_utilizados' => 0,
                'preguntas_utilizadas' => 0,
                'total_preguntas_disponibles' => 0,
                'total_tipos_votacion' => 0
            ];
        }
    }

    # Método para obtener planchas agrupadas por facultad/agrupador #
    public function obtenerPlanchasPorAgrupador() {
        try {
            $consulta = $this->ejecutarConsulta("
                SELECT 
                    ts.AGRUPADOR,
                    ts.TIPO_SOLICITUD,
                    COUNT(op.ID_OPCION_PREGUNTA) as total_planchas,
                    COUNT(CASE WHEN op.RUTA_IMAGEN IS NOT NULL AND op.RUTA_IMAGEN != '' THEN 1 END) as con_imagen_local
                FROM ugc_tipo_solicitud ts
                LEFT JOIN ugc_solicitud_pregunta sp ON ts.ID_TIPO_SOLICITUD = sp.ID_TIPO_SOLICITUD
                LEFT JOIN ugc_opcion_pregunta op ON sp.ID_SOLICITUD_PREGUNTA = op.ID_SOLICITUD_PREGUNTA AND op.ESTADO = 'ACTIVO'
                WHERE ts.SERVICIO = 'VOT' AND ts.ESTADO = 'ACTIVO'
                GROUP BY ts.AGRUPADOR, ts.TIPO_SOLICITUD
                ORDER BY ts.AGRUPADOR ASC, ts.TIPO_SOLICITUD ASC
            ");
            return $consulta->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    # Método legacy para compatibilidad #
    public function obtenerPreguntasVotacion() {
        return $this->obtenerPreguntasDisponibles();
    }
}
?>
