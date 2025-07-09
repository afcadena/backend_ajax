<?php
namespace app\models;
use \PDO;

if(file_exists(__DIR__."/../config/server.php")){
    require_once __DIR__."/../config/server.php";
}

class mainModel {
    private $server = DB_SERVER;
    private $db     = DB_NAME;
    private $user   = DB_USER;
    private $pass   = DB_PASSWORD;

    protected function conectar(){
        $conexion = new PDO("mysql:host=".$this->server.";dbname=".$this->db, $this->user, $this->pass);
        $conexion->exec("SET CHARACTER SET utf8");
        return $conexion;
    }

    protected function ejecutarConsulta($consulta){
        $sql = $this->conectar()->prepare($consulta);
        $sql->execute();
        return $sql;
    }

    public function limpiarCadena($cadena){
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        return htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
    }

    protected function guardarDatos($tabla, $datos){
        $query = "INSERT INTO $tabla (";
        $query .= implode(",", array_column($datos, "campo_nombre")) . ") VALUES (";
        $query .= implode(",", array_column($datos, "campo_marcador")) . ")";

        $sql = $this->conectar()->prepare($query);
        foreach($datos as $clave){
            $sql->bindParam($clave["campo_marcador"], $clave["campo_valor"]);
        }
        $sql->execute();
        return $sql;
    }

    public function seleccionarDatos($tipo, $tabla, $campo, $id){
        if($tipo == "Unico"){
            $sql = $this->conectar()->prepare("SELECT * FROM $tabla WHERE $campo = :ID");
            $sql->bindParam(":ID", $id);
        } elseif($tipo == "Normal"){
            $sql = $this->conectar()->prepare("SELECT $campo FROM $tabla");
        }
        $sql->execute();
        return $sql;
    }

    protected function actualizarDatos($tabla, $datos, $condicion){
        $query = "UPDATE $tabla SET ";
        $campos = [];
        foreach($datos as $clave){
            $campos[] = $clave["campo_nombre"] . "=" . $clave["campo_marcador"];
        }
        $query .= implode(",", $campos);
        $query .= " WHERE " . $condicion["condicion_campo"] . "=" . $condicion["condicion_marcador"];

        $sql = $this->conectar()->prepare($query);
        foreach($datos as $clave){
            $sql->bindParam($clave["campo_marcador"], $clave["campo_valor"]);
        }
        $sql->bindParam($condicion["condicion_marcador"], $condicion["condicion_valor"]);
        $sql->execute();
        return $sql;
    }

    protected function eliminarRegistro($tabla, $campo, $id){
        $sql = $this->conectar()->prepare("DELETE FROM $tabla WHERE $campo = :id");
        $sql->bindParam(":id", $id);
        $sql->execute();
        return $sql;
    }
    
}
