<?php
class Libros {
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

    public function insertar($datosLibro) {
        $datosLibro = $this->limpiarDatos($datosLibro);
        

        if (is_numeric($datosLibro['idAutor'])) {
            $consulta = "INSERT INTO libros (idLibro, titulo, genero, idAutor, numeroPaginas, numeroEjemplares) 
                        VALUES (NULL, '".$datosLibro['titulo']."', '".$datosLibro['genero']."', '".$datosLibro['idAutor']."', '".$datosLibro['numeroPaginas']."', '".$datosLibro['numeroEjemplares']."');";
            
            return $this->conexion->query($consulta);
        } else {
            return false; 
        }
    }
    

    public function insertarCampos ($idLibro, $titulo, $genero, $idAutor, $numeroPaginas, $numeroEjemplares){
        $consulta = "INSERT INTO libros (idLibro, titulo, genero, idAutor, numeroPaginas, numeroEjemplares) VALUES (NULL, '".$titulo."', '".$genero."', '".$idAutor."', '".$numeroPaginas."', '".$numeroEjemplares."');";
        
        return $this->conexion->query($consulta);
    }

    public function actualizaLibro ($datosLibro) {
        $set = "SET ";
        
        if ($datosLibro['titulo'] != NULL)
            $set .= "titulo = '".mysqli_real_escape_string($this->conexion, $datosLibro['titulo'])."', ";
        
        if ($datosLibro['genero'] != NULL)
            $set .= "genero = '".mysqli_real_escape_string($this->conexion, $datosLibro['genero'])."', ";
        
        if ($datosLibro['idAutor'] != NULL)
            $set .= "idAutor = '".mysqli_real_escape_string($this->conexion, $datosLibro['idAutor'])."', ";
        
        if ($datosLibro['numeroPaginas'] != NULL)
            $set .= "numeroPaginas = '".mysqli_real_escape_string($this->conexion, $datosLibro['numeroPaginas'])."', ";
        
        if ($datosLibro['numeroEjemplares'] != NULL)
            $set .= "numeroEjemplares = '".mysqli_real_escape_string($this->conexion, $datosLibro['numeroEjemplares'])."', ";
        
        $set = substr($set, 0, -2);
        
        if ($datosLibro['idLibro'] != NULL) {
            $idLibro = mysqli_real_escape_string($this->conexion, $datosLibro['idLibro']);
            $consulta = "UPDATE libros $set WHERE idLibro = $idLibro;";
            return $this->conexion->query($consulta);
        } else {
            return false;
        }
    }

    public function eliminar ($idLibro){
        $consulta = "DELETE FROM libros WHERE idLibro = ".$idLibro.";";
        
        return $this->conexion->query($consulta);
    }

    public function consultarLibro($idLibro = NULL){
        if ($idLibro != NULL){
            $consulta = "SELECT * FROM libros WHERE idLibro = ".$idLibro.";";
            $datos = $this->conexion->query($consulta);
            return $datos->fetch_assoc();
        } else {
            $consulta = "SELECT * FROM libros;";
            $result = $this->conexion->query($consulta);
            
            $libros = array(); 
            
            while ($row = $result->fetch_assoc()) {
                $libros[] = $row;
            }
            
            return $libros;
        }
    }
    

    public function consultarCampos($titulo = null, $genero = null, $idAutor = null, $numeroPaginas = null, $numeroEjemplares = null) {
        $where = array();
        if ($titulo != NULL){
            $where[] = "titulo = '$titulo'";
        }
        if ($genero != NULL){
            $where[] = "genero = '$genero'";
        }
        if ($idAutor != NULL){
            $where[] = "idAutor = $idAutor";
        }
        if ($numeroPaginas != NULL){
            $where[] = "numeroPaginas = $numeroPaginas";
        }
        if ($numeroEjemplares != NULL){
            $where[] = "numeroEjemplares = $numeroEjemplares";
        }
        if ($where == "WHERE "){
            unset ($where);
        }
        $where = implode(" AND ", $where);
        $consulta = "SELECT * FROM libros WHERE $where;";   
        $resultado = $this->conexion->query($consulta);
        return $resultado->fetch_all(MYSQLI_ASSOC);
        

    }
}
?>