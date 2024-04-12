<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tramos por Carretera</title>
</head>

<body>
    <h1>Tramos por Carretera</h1>

    <?php
    // Verificar si se recibió el ID de la carretera en la URL
    if (isset($_GET['carretera']) && !empty($_GET['carretera'])) {
        // Obtener el ID de la carretera de la URL
        $id_carretera = $_GET['carretera'];

        // Incluir el archivo de conexión
        require_once("./sources/conexion.php");

        // Crear una instancia de la clase de conexión
        $conexion = new Conexion();

        // Consulta SQL para obtener los tramos de la carretera especificada
        $sql = "SELECT ID_TRAMO, NOMBRE, ES_ASFALTADA, KM_ASFALTADA, KM_NO_ASFALTADO FROM TRAMO WHERE ID_CARRETERA = $id_carretera";
        $resultado = $conexion->consultar($sql);

        // Verificar si se obtuvieron resultados
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            // Mostrar la tabla de tramos
            echo '<table border="1">';
            echo '<tr><th>ID Tramo</th><th>Nombre</th><th>Es Asfaltada</th><th>KM Asfaltada</th><th>KM No Asfaltado</th></tr>';
            // Recorrer los resultados y mostrar cada tramo en una fila de la tabla
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $id_tramo = $fila['ID_TRAMO'];
                $nombre_tramo = $fila['NOMBRE'];
                $es_asfaltada = $fila['ES_ASFALTADA'] ? 'Sí' : 'No';
                $km_asfaltada = $fila['KM_ASFALTADA'];
                $km_no_asfaltado = $fila['KM_NO_ASFALTADO'];
                echo "<tr><td>$id_tramo</td><td><a href='tramo_comunidad.php?tramo=$id_tramo'>$nombre_tramo</a></td><td>$es_asfaltada</td><td>$km_asfaltada</td><td>$km_no_asfaltado</td></tr>";
            }
            echo '</table>';
        } else {
            // Si no hay tramos asociados a la carretera
            echo '<p>No se encontraron tramos para esta carretera.</p>';
        }

        // Cerrar la conexión a la base de datos
        $conexion->cerrar();
    } else {
        // Si no se proporcionó el ID de la carretera en la URL
        echo '<p>No se proporcionó una carretera válida.</p>';
    }
    ?>

    <a href="index.php">Volver al índice</a>
</body>

</html>
