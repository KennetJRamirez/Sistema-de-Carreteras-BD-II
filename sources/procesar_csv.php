<?php
require_once("conexion.php");

// Crear una instancia de la clase de conexión
$conexion = new Conexion();

// Verificar si se recibió un archivo CSV y se seleccionó una tabla
if(isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK && isset($_POST['tabla'])) {
    // Obtener la tabla seleccionada
    $tabla = $_POST['tabla'];

    // Definir las columnas de la tabla seleccionada
    $columnas = array();
    switch ($tabla) {
        // En caso sea la tabla 'carretera', se definen las columnas 'NOMBRE', 'ID_REGION' y 'ID_CATEGORIA'
        case 'carretera':
            $columnas = array('NOMBRE', 'ID_REGION', 'ID_CATEGORIA');
            break;
        // En caso sea la tabla 'tramo', se definen las columnas 'NOMBRE', 'ID_CARRETERA', 'ES_ASFALTADA', 'KM_ASFALTADA' y 'KM_NO_ASFALTADO'
        case 'tramo':
            $columnas = array('NOMBRE', 'ID_CARRETERA', 'ES_ASFALTADA', 'KM_ASFALTADA', 'KM_NO_ASFALTADO');
            break;
        // En caso sea la tabla 'comunidad', se define la columna 'NOMBRE'
        case 'comunidad':
            $columnas = array('NOMBRE');
            break;
        // En caso sea la tabla 'tramo_comunidad', se definen las columnas 'ID_TRAMO', 'ID_COMUNIDAD', 'KM_INICIO' y 'KM_FIN'
        case 'tramo_comunidad':
            $columnas = array('ID_TRAMO', 'ID_COMUNIDAD', 'KM_INICIO', 'KM_FIN');
            break;
        // En caso sea la tabla 'carretera_comunidad', se definen las columnas 'ID_CARRETERA', 'ID_COMUNIDAD' y 'KM'
        case 'carretera_comunidad':
            $columnas = array('ID_CARRETERA', 'ID_COMUNIDAD', 'KM');
            break;
        default:
        // En caso no se haya seleccionado una tabla válida, se muestra un mensaje de error y se termina la ejecución del script
            echo "Error: Tabla no válida.";
            exit();
    }

    // Obtener el archivo CSV subido y su ruta temporal
    $archivo_tmp = $_FILES['csv_file']['tmp_name'];

    // Abrir el archivo CSV en modo lectura
    $archivo = fopen($archivo_tmp, "r");

    // Verificar si se pudo abrir el archivo CSV
    if($archivo === false) {
        echo "Error: No se pudo abrir el archivo CSV.";
        exit();
    }

    // Leer la primera fila del archivo CSV que contiene los nombres de las columnas
    fgetcsv($archivo);

    // Recorrer el archivo CSV línea por línea
    while(($fila = fgetcsv($archivo)) !== false) {
        // Verificar si la fila tiene el número correcto de columnas
        if(count($fila) != count($columnas)) {
            echo "Error: La fila no tiene el formato esperado para la tabla $tabla.<br>";
            continue; // Pasar a la siguiente fila
        }

        // Escapar los valores de la fila para evitar inyección de SQL
        $valores = array_map(function($valor) use ($conexion) {
            return mysqli_real_escape_string($conexion->getConexion(), trim($valor));
        }, $fila);

        // Crear la consulta SQL para insertar el registro en la tabla
        $query = "INSERT INTO $tabla (".implode(', ', $columnas).") VALUES ('".implode("', '", $valores)."')";

        // Ejecutar la consulta SQL
        if(mysqli_query($conexion->getConexion(), $query)) {
            echo "Registro insertado exitosamente en $tabla: ".implode(', ', $valores)."<br>";
        } else {
            echo "Error al insertar el registro en $tabla: " . mysqli_error($conexion->getConexion()) . "<br>";
        }
    }

    // Cerrar el archivo CSV
    fclose($archivo);
} else {
    // Mostrar el formulario para subir un archivo CSV si no se ha enviado un archivo o no se ha seleccionado una tabla
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir archivo CSV</title>
</head>

<body>
    <h1>Subir archivo CSV</h1>
    <form action="procesar_csv.php" method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".csv" required>
        <select name="tabla" required>
            <option value="" disabled selected>Selecciona una tabla</option>
            <option value="carretera">Carretera</option>
            <option value="tramo">Tramo</option>
            <option value="comunidad">Comunidad</option>
            <option value="tramo_comunidad">Tramo_Comunidad</option>
            <option value="carretera_comunidad">Carretera_Comunidad</option>
        </select>
        <button type="submit">Subir archivo</button>
    </form>
</body>

</html>
<?php
}

// Cerrar la conexión a la base de datos
$conexion->cerrar();
?>
