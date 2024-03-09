<?php
class Autores {
    private $conexion;

    private function limpiarDatos($datos){
        $datosLimpio = array();
        foreach ($datos as $key => $value) {
            $datosLimpio[$key] = $this->conexion->real_escape_string($value);
        }
        return $datosLimpio;
    }
    public function __construct($conexion = NULL){
        $this->conexion = $conexion;

    }

    public function insertar ($datosAutor){
        $datosAutor = $this->limpiarDatos($datosAutor);
        
        $consulta = "INSERT INTO autores (idAutor, nombre, apellidos, pais) VALUES (NULL, '".$datosAutor['nombre']."', '".$datosAutor['apellidos']."', '".$datosAutor['pais']."');";
        
        return $this->conexion->query($consulta);
    }

    public function insertarCampos ($idAutor, $nombre, $apellidos, $pais){
        $consulta = "INSERT INTO autores (idAutor, nombre, apellidos, pais) VALUES (NULL, '".$nombre."', '".$apellidos."', '".$pais."');";
        
        return $this->conexion->query($consulta);
    }

    public function actualizaAutor ($datosAutor){
        $set = "SET ";
        if ($datosAutor['nombre'] != NULL){
            $set .= "nombre = '".$datosAutor['nombre']."', ";
        }
        if ($datosAutor['apellidos'] != NULL){
            $set .= "apellidos = '".$datosAutor['apellidos']."', ";
        }
        if ($datosAutor['pais'] != NULL){
            $set .= "pais = '".$datosAutor['pais']."', ";
        }
        $set = substr($set, 0, -2);
        $consulta = "UPDATE autores $set WHERE idAutor = ".$datosAutor['idAutor'].";";
        return $this->conexion->query($consulta);
    }

    public function eliminar ($idAutor){
        $consulta = "DELETE FROM autores WHERE idAutor = ".$idAutor.";";
        
        return $this->conexion->query($consulta);
    }

    public function consultarAutor($idAutor = NULL){
        if ($idAutor != NULL){
            $consulta = "SELECT * FROM autores WHERE idAutor = $idAutor;";
            $resultado = $this->conexion->query($consulta);
            return $resultado->fetch_all(MYSQLI_ASSOC);
            if ($resultado) {
                return $resultado->fetch_assoc();
            }
        } else {
            $consulta = "SELECT * FROM autores;";
            $resultado = $this->conexion->query($consulta);
    
            if ($resultado) {
                $data = array();
                while ($row = $resultado->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    
        return false;
    }
    
    public function consultarCampos ($idAutor=NULL, $nombre=NULl, $apellidos=NULL, $pais=NULL ) {
        $where = "WHERE ";
        if ($idAutor != NULL){
            $where .= "idAutor = $idAutor AND ";
        } else {
        if ($nombre != NULL){
            $where .= "nombre = $nombre AND ";
        }
        if ($apellidos != NULL){
            $where .= "apellidos = $apellidos AND ";
        }
        if ($pais != NULL){
            $where .= "pais = $pais AND ";
        }
        if ($where == "WHERE "){
            $where = "";
        } else {
            $where = substr($where, 0, -5);
        }

        $consulta = "SELECT * FROM autores $where;";
        $this -> conexion -> query($consulta);
        return $this -> conexion -> fetch_all(MYSQLI_ASSOC);
        }
    }


}


?>