<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carreteras por Región</title>
</head>

<body>
    <h1>Carreteras por Región</h1>

    <?php
    // Verificar si se recibió el ID de la región en la URL
    if (isset($_GET['region']) && !empty($_GET['region'])) {
        // Obtener el ID de la región de la URL
        $id_region = $_GET['region'];

        // Incluir el archivo de conexión
        require_once("./sources/conexion.php");

        // Crear una instancia de la clase de conexión
        $conexion = new Conexion();

        // Consulta SQL para obtener las carreteras de la región especificada
        $sql = "SELECT ID_CARRETERA, NOMBRE FROM CARRETERA WHERE ID_REGION = $id_region";
        $resultado = $conexion->consultar($sql);

        // Verificar si se obtuvieron resultados
        if (mysqli_num_rows($resultado) > 0) {
            // Iniciar la lista de carreteras
            echo '<ul>';
            // Recorrer los resultados y mostrar cada carretera
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $id_carretera = $fila['ID_CARRETERA'];
                $nombre_carretera = $fila['NOMBRE'];
                echo "<li><a href='tramos_por_carretera.php?carretera=$id_carretera'>$nombre_carretera</a></li>";
            }
            // Cerrar la lista de carreteras
            echo '</ul>';
        } else {
            // Si no hay carreteras asociadas a la región
            echo '<p>No se encontraron carreteras en esta región.</p>';
        }

        // Cerrar la conexión a la base de datos
        $conexion->cerrar();
    } else {
        // Si no se proporcionó el ID de la región en la URL
        echo '<p>No se proporcionó una región válida.</p>';
    }
    ?>

    <a href="index.php">Volver al índice</a>
</body>

</html>
