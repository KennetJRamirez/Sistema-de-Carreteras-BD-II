<?php
class Conexion {
    private $mysqli;

    // Constructor de la clase
    public function __construct() {
        $this->conectarBaseDatos();
    }

    // Método para conectar a la base de datos
    private function conectarBaseDatos() {
        $host = 'localhost';
        $usuario = 'root';
        $contrasena = '';
        $base_datos = 'carretera_db';

        $this->mysqli = mysqli_connect($host, $usuario, $contrasena, $base_datos);

        // Verificar si se ha establecido la conexión
        if (!$this->mysqli) {
            die("Error de conexión: " . mysqli_connect_error());
        }
    }

    // Método para obtener la conexión a la base de datos
    public function getConexion() {
        return $this->mysqli;
    }

    // Método para consultar la base de datos
    public function consultar($query) {
        $resultado = mysqli_query($this->mysqli, $query);
        return $resultado;
    }

    // Método para cerrar la conexión a la base de datos
    public function cerrar() {
        mysqli_close($this->mysqli);
    }
}
?>
