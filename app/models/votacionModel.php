<?php
namespace app\models;
use app\models\mainModel;

class votacionModel extends mainModel {
    
    # Obtener tipos de votación disponibles #
    public function obtenerTiposVotacion() {
        $sql = $this->conectar()->prepare("
            SELECT * FROM ugc_tipos_votacion 
            WHERE ACTIVO = 1 
            ORDER BY NOMBRE ASC
        ");
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    # Obtener usuarios disponibles para asignar como votantes #
    public function obtenerUsuariosDisponibles() {
        $sql = $this->conectar()->prepare("
            SELECT ID_USUARIO, NOMBRE, EMAIL, IDENTIFICACION 
            FROM ugc_usuarios 
            WHERE ESTADO = 'ACTIVO' 
            ORDER BY NOMBRE ASC
        ");
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    # Verificar permisos de usuario #
    public function verificarPermisosUsuario($id_usuario, $permiso) {
        $sql = $this->conectar()->prepare("
            SELECT COUNT(*) as tiene_permiso
            FROM ugc_usuarios_permisos up
            INNER JOIN ugc_permisos p ON up.ID_PERMISO = p.ID_PERMISO
            WHERE up.ID_USUARIO = :id_usuario 
            AND p.NOMBRE_PERMISO = :permiso
            AND up.ACTIVO = 1
        ");
        $sql->bindParam(":id_usuario", $id_usuario);
        $sql->bindParam(":permiso", $permiso);
        $sql->execute();
        $resultado = $sql->fetch(\PDO::FETCH_ASSOC);
        return $resultado['tiene_permiso'] > 0;
    }

    # Obtener estadísticas de votación #
    public function obtenerEstadisticasVotacion($id_votacion) {
        $sql = $this->conectar()->prepare("
            SELECT 
                v.TITULO,
                v.FECHA_INICIO,
                v.FECHA_FIN,
                v.ESTADO,
                COUNT(DISTINCT vt.ID_VOTANTE) as total_votantes_habilitados,
                COUNT(DISTINCT vo.ID_VOTO) as total_votos_emitidos,
                ROUND((COUNT(DISTINCT vo.ID_VOTO) / COUNT(DISTINCT vt.ID_VOTANTE)) * 100, 2) as porcentaje_participacion
            FROM ugc_votaciones v
            LEFT JOIN ugc_votantes vt ON v.ID_VOTACION = vt.ID_VOTACION AND vt.ESTADO = 'HABILITADO'
            LEFT JOIN ugc_votos vo ON v.ID_VOTACION = vo.ID_VOTACION
            WHERE v.ID_VOTACION = :id_votacion
            GROUP BY v.ID_VOTACION
        ");
        $sql->bindParam(":id_votacion", $id_votacion);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    # Obtener log de actividades de votación #
    public function obtenerLogActividades($id_votacion) {
        $sql = $this->conectar()->prepare("
            SELECT 
                l.FECHA_ACTIVIDAD,
                l.TIPO_ACTIVIDAD,
                l.DESCRIPCION,
                u.NOMBRE as usuario_nombre,
                l.IP_ADDRESS
            FROM ugc_log_votaciones l
            LEFT JOIN ugc_usuarios u ON l.ID_USUARIO = u.ID_USUARIO
            WHERE l.ID_VOTACION = :id_votacion
            ORDER BY l.FECHA_ACTIVIDAD DESC
        ");
        $sql->bindParam(":id_votacion", $id_votacion);
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    # Registrar actividad en log #
    public function registrarActividad($id_votacion, $id_usuario, $tipo_actividad, $descripcion) {
        $datos_log = [
            ["campo_nombre" => "ID_VOTACION", "campo_marcador" => ":IdVotacion", "campo_valor" => $id_votacion],
            ["campo_nombre" => "ID_USUARIO", "campo_marcador" => ":IdUsuario", "campo_valor" => $id_usuario],
            ["campo_nombre" => "TIPO_ACTIVIDAD", "campo_marcador" => ":TipoActividad", "campo_valor" => $tipo_actividad],
            ["campo_nombre" => "DESCRIPCION", "campo_marcador" => ":Descripcion", "campo_valor" => $descripcion],
            ["campo_nombre" => "FECHA_ACTIVIDAD", "campo_marcador" => ":FechaActividad", "campo_valor" => date("Y-m-d H:i:s")],
            ["campo_nombre" => "IP_ADDRESS", "campo_marcador" => ":IpAddress", "campo_valor" => $_SERVER['REMOTE_ADDR']]
        ];

        return $this->guardarDatos("ugc_log_votaciones", $datos_log);
    }

    # Validar integridad de votación #
    public function validarIntegridadVotacion($id_votacion) {
        $errores = [];

        // Verificar que todas las preguntas tengan opciones
        $preguntas_sin_opciones = $this->ejecutarConsulta("
            SELECT p.ID_PREGUNTA, p.PREGUNTA
            FROM ugc_preguntas_votacion p
            LEFT JOIN ugc_opciones_votacion o ON p.ID_PREGUNTA = o.ID_PREGUNTA
            WHERE p.ID_VOTACION = '$id_votacion' 
            AND p.TIPO_PREGUNTA IN ('SELECCION_UNICA', 'SELECCION_MULTIPLE')
            AND o.ID_OPCION IS NULL
        ")->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($preguntas_sin_opciones)) {
            $errores[] = "Existen preguntas sin opciones configuradas";
        }

        // Verificar que haya al menos un votante asignado
        $total_votantes = $this->ejecutarConsulta("
            SELECT COUNT(*) as total 
            FROM ugc_votantes 
            WHERE ID_VOTACION = '$id_votacion' AND ESTADO = 'HABILITADO'
        ")->fetch(\PDO::FETCH_ASSOC);

        if ($total_votantes['total'] == 0) {
            $errores[] = "No hay votantes asignados a esta votación";
        }

        return $errores;
    }
}
