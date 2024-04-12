<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunidades del País</title>
</head>

<body>
    <h1>Comunidades del País</h1>
    <?php
    // Verificar si se recibió el ID de la comunidad en la URL
    if (isset($_GET['comunidad']) && !empty($_GET['comunidad'])) {
        // Obtener el ID de la comunidad de la URL
        $id_comunidad = $_GET['comunidad'];

        // Incluir el archivo de conexión
        require_once("./sources/conexion.php");

        // Crear una instancia de la clase de conexión
        $conexion = new Conexion();

        // Consulta SQL para obtener los detalles de la comunidad y las carreteras asociadas
        $sql = "SELECT cc.ID_CARRETERA_COMUNIDAD, cc.ID_CARRETERA, c.NOMBRE AS NOMBRE_CARRETERA, cc.KM, co.ID_COMUNIDAD, co.NOMBRE AS NOMBRE_COMUNIDAD
                FROM CARRETERA_COMUNIDAD cc
                INNER JOIN CARRETERA c ON cc.ID_CARRETERA = c.ID_CARRETERA
                INNER JOIN COMUNIDAD co ON cc.ID_COMUNIDAD = co.ID_COMUNIDAD
                WHERE cc.ID_COMUNIDAD = $id_comunidad";

        $resultado = $conexion->consultar($sql);

        // Verificar si se obtuvieron resultados
        if (mysqli_num_rows($resultado) > 0) {
            // Iniciar la tabla de detalles de la comunidad y las carreteras asociadas
            echo '<table border="1">';
            echo '<tr><th>ID Carretera Comunidad</th><th>ID Carretera</th><th>Nombre Carretera</th><th>KM</th><th>ID Comunidad</th><th>Nombre Comunidad</th></tr>';

            // Recorrer los resultados y mostrar cada carretera asociada a la comunidad en una fila de la tabla
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $id_carretera_comunidad = $fila['ID_CARRETERA_COMUNIDAD'];
                $id_carretera = $fila['ID_CARRETERA'];
                $nombre_carretera = $fila['NOMBRE_CARRETERA'];
                $km = $fila['KM'];
                $id_comunidad = $fila['ID_COMUNIDAD'];
                $nombre_comunidad = $fila['NOMBRE_COMUNIDAD'];
                echo "<tr><td>$id_carretera_comunidad</td><td>$id_carretera</td><td>$nombre_carretera</td><td>$km</td><td>$id_comunidad</td><td>$nombre_comunidad</td></tr>";
            }

            // Cerrar la tabla de detalles de la comunidad y las carreteras asociadas
            echo '</table>';
        } else {
            // Si no hay carreteras asociadas a la comunidad
            echo '<p>No se encontraron carreteras asociadas a esta comunidad.</p>';
        }

        // Cerrar la conexión a la base de datos
        $conexion->cerrar();
    } else {
        // Si no se proporcionó el ID de la comunidad en la URL
        echo '<p>No se proporcionó una comunidad válida.</p>';
    }
    ?>

    <a href="index.php">Volver al índice</a>
</body>

</html>
