<?php
// Verificar si se recibió el ID del tramo en la URL
if (isset($_GET['tramo']) && !empty($_GET['tramo'])) {
    // Obtener el ID del tramo de la URL
    $id_tramo = $_GET['tramo'];

    // Incluir el archivo de conexión
    require_once("./sources/conexion.php");

    // Crear una instancia de la clase de conexión
    $conexion = new Conexion();

    // Consulta SQL para obtener los detalles del tramo desde la tabla TRAMO
    $sql_tramo = "SELECT * FROM TRAMO WHERE ID_TRAMO = $id_tramo";

    // Consulta SQL para obtener los detalles del tramo desde la tabla TRAMO_COMUNIDAD
    $sql_tramo_comunidad = "SELECT * FROM TRAMO_COMUNIDAD WHERE ID_TRAMO = $id_tramo";

    // Obtener los resultados de la consulta del tramo
    $resultado_tramo = $conexion->consultar($sql_tramo);

    // Verificar si se obtuvieron resultados del tramo
    if ($resultado_tramo && mysqli_num_rows($resultado_tramo) > 0) {
        // Mostrar los detalles del tramo desde la tabla TRAMO
        echo '<h2>Detalles del Tramo</h2>';
        echo '<table border="1">';
        echo '<tr><th>ID Tramo</th><th>Nombre</th><th>Es Asfaltada</th><th>KM Asfaltada</th><th>KM No Asfaltado</th></tr>';
        $fila_tramo = mysqli_fetch_assoc($resultado_tramo);
        echo '<tr>';
        echo "<td>{$fila_tramo['ID_TRAMO']}</td>";
        echo "<td>{$fila_tramo['NOMBRE']}</td>";
        echo "<td>" . ($fila_tramo['ES_ASFALTADA'] ? 'Sí' : 'No') . "</td>";
        echo "<td>{$fila_tramo['KM_ASFALTADA']}</td>";
        echo "<td>{$fila_tramo['KM_NO_ASFALTADO']}</td>";
        echo '</tr>';
        echo '</table>';

        // Obtener los resultados de la consulta de la tabla TRAMO_COMUNIDAD
        $resultado_tramo_comunidad = $conexion->consultar($sql_tramo_comunidad);

        // Verificar si se obtuvieron resultados de la tabla TRAMO_COMUNIDAD
        if ($resultado_tramo_comunidad && mysqli_num_rows($resultado_tramo_comunidad) > 0) {
            // Mostrar las comunidades asociadas al tramo desde la tabla TRAMO_COMUNIDAD
            echo '<h2>Comunidades Asociadas</h2>';
            echo '<table border="1">';
            echo '<tr><th>ID Comunidad</th><th>Nombre</th><th>KM Inicio</th><th>KM Fin</th></tr>';
            while ($fila_tramo_comunidad = mysqli_fetch_assoc($resultado_tramo_comunidad)) {
                echo '<tr>';
                echo "<td>{$fila_tramo_comunidad['ID_COMUNIDAD']}</td>";
                
                // Consulta SQL para obtener los detalles de la comunidad desde la tabla COMUNIDAD
                $id_comunidad = $fila_tramo_comunidad['ID_COMUNIDAD'];
                $sql_comunidad = "SELECT * FROM COMUNIDAD WHERE ID_COMUNIDAD = $id_comunidad";
                $resultado_comunidad = $conexion->consultar($sql_comunidad);
                $fila_comunidad = mysqli_fetch_assoc($resultado_comunidad);
                echo "<td><a href='comunidades.php?comunidad={$fila_comunidad['ID_COMUNIDAD']}'>{$fila_comunidad['NOMBRE']}</a></td>";
                
                echo "<td>{$fila_tramo_comunidad['KM_INICIO']}</td>";
                echo "<td>{$fila_tramo_comunidad['KM_FIN']}</td>";
                echo '</tr>';
            }
            echo '</table>';
        } else {
            // Si no hay comunidades asociadas al tramo desde la tabla TRAMO_COMUNIDAD
            echo '<p>No se encontraron comunidades asociadas a este tramo.</p>';
        }
    } else {
        // Si no se encontró el tramo en la tabla TRAMO
        echo '<p>No se encontró información del tramo.</p>';
    }

    // Cerrar la conexión a la base de datos
    $conexion->cerrar();
} else {
    // Si no se proporcionó el ID del tramo en la URL
    echo '<p>No se proporcionó un tramo válido.</p>';
}

// Enlace para volver al índice
echo '<a href="index.php">Volver al índice</a>';
?>
