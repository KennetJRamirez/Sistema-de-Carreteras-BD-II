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

        // Consulta SQL base para obtener las carreteras de la región especificada
        $sql = "SELECT c.ID_CARRETERA, c.NOMBRE, c.ID_REGION, c.ID_CATEGORIA FROM CARRETERA c WHERE c.ID_REGION = $id_region";

        // Verificar si se seleccionó un tipo de carretera
        if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
            $id_categoria = $_GET['categoria'];
            $sql .= " AND c.ID_CATEGORIA = $id_categoria";
        }

        // Ejecutar la consulta
        $resultado = $conexion->consultar($sql);

        // Verificar si se obtuvieron resultados
        if (mysqli_num_rows($resultado) > 0) {
            // Iniciar la tabla de carreteras
            echo '<table border="1">';
            echo '<tr><th>Nombre</th><th>ID</th><th>Región</th><th>Categoría</th></tr>';
            // Recorrer los resultados y mostrar cada carretera en una fila de la tabla
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $id_carretera = $fila['ID_CARRETERA'];
                $nombre_carretera = $fila['NOMBRE'];
                $id_region = $fila['ID_REGION'];
                $id_categoria = $fila['ID_CATEGORIA'];
                echo "<tr><td><a href='tramos.php?carretera=$id_carretera'>$nombre_carretera</a></td><td>$id_carretera</td><td>$id_region</td><td>$id_categoria</td></tr>";
            }
            // Cerrar la tabla de carreteras
            echo '</table>';
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

    <h2>Filtrar por Tipo de Carretera:</h2>
    <form action="" method="GET">
        <label for="categoria">Seleccione un tipo de carretera:</label>
        <select name="categoria" id="categoria">
            <option value="">Todos</option>
            <option value="1">Local</option>
            <option value="2">Comercial</option>
            <option value="3">Regional</option>
            <option value="4">Nacional</option>
        </select>
        <input type="hidden" name="region" value="<?php echo $id_region; ?>">
        <button type="submit">Filtrar</button>
    </form>

    <a href="index.php">Volver al índice</a>
</body>

</html>
