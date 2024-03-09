<?php
class Usuarios {
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

    public function insertar($datosUsuario) {
        $datosUsuario = $this->limpiarDatos($datosUsuario);
        $consulta = "INSERT INTO usuarios (nombre, apellidos, salt, login, password, rol) VALUES ('".$datosUsuario['nombre']."', '".$datosUsuario['apellidos']."', '".$datosUsuario['salt']."', '".$datosUsuario['login']."', '".$datosUsuario['password']."', '".$datosUsuario['rol']."');";

    }
    

    public function insertarCampos ($nombre = NULL, $apellidos = NULL, $salt = NULL, $login = NULL, $password = NULL, $rol = NULL) {
        $consulta = "INSERT INTO usuarios (nombre, apellidos, salt, login, password, rol) VALUES ('".$nombre."', '".$apellidos."', '".$salt."', '".$login."', '".$password."', '".$rol."');";
        return $this->conexion->query($consulta);
    }

    public function actualizarUsuario($datosUsuario) {
        if (is_array($datosUsuario) && !empty($datosUsuario['login'])) {
            $set = "SET ";
            
            if (!empty($datosUsuario['nombre'])){
                $set .= "nombre = '".$datosUsuario['nombre']."', ";
            }
            if (!empty($datosUsuario['apellidos'])){
                $set .= "apellidos = '".$datosUsuario['apellidos']."', ";
            }
            if (!empty($datosUsuario['salt'])){
                $set .= "salt = '".$datosUsuario['salt']."', ";
            }
            if (!empty($datosUsuario['password'])){
                $set .= "password = '".$datosUsuario['password']."', ";
            }
            if (!empty($datosUsuario['rol'])){
                $set .= "rol = '".$datosUsuario['rol']."', ";
            }
            $set = rtrim($set, ', '); // Elimina la última coma y espacio agregados
            
            $consulta = "UPDATE usuarios ".$set." WHERE login = '".$datosUsuario['login']."';";
            return $this->conexion->query($consulta);
        } else {
            return false; // O manejar el caso donde $datosUsuario no es un array válido
        }
    }
    

    public function eliminar ($login){
        $consulta = "DELETE FROM usuarios WHERE login = '".$login."';";
        
        return $this->conexion->query($consulta);
    }

    public function consultarUsuario($login = NULL){
        if ($login != NULL){
            $consulta = "SELECT * FROM usuarios WHERE login = ?";
            $stmt = $this->conexion->prepare($consulta);
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $datos = $stmt->get_result()->fetch_assoc();
            return $datos;
        } else {
            $consulta = "SELECT * FROM usuarios";
            $result = $this->conexion->query($consulta);
            
            $usuarios = array(); 
            
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
            
            return $usuarios;
        }
    }
    

    public function consultarCampos($nombre = NULL, $apellidos = NULL, $salt = NULL, $login = NULL, $password = NULL, $rol = NULL){
        $consulta = "SELECT * FROM usuarios WHERE ";
        if ($nombre != NULL){
            $consulta .= "nombre = '".$nombre."' AND ";
        }
        if ($apellidos != NULL){
            $consulta .= "apellidos = '".$apellidos."' AND ";
        }
        if ($salt != NULL){
            $consulta .= "salt = '".$salt."' AND ";
        }
        if ($login != NULL){
            $consulta .= "login = '".$login."' AND ";
        }
        if ($password != NULL){
            $consulta .= "password = '".$password."' AND ";
        }
        if ($rol != NULL){
            $consulta .= "rol = '".$rol."' AND ";
        }
        $consulta = substr($consulta, 0, -5);
        $consulta .= ";";
        $result = $this->conexion->query($consulta);
        
        $usuarios = array(); 
        
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        
        return $usuarios;

    }

    public function actualizarFotoPerfil($login, $rutaImagen) {
        $consulta = "UPDATE usuarios SET foto_perfil = ? WHERE login = ?";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->bind_param("ss", $rutaImagen, $login);
        $stmt->execute();
        $stmt->close();
    }
}
?>