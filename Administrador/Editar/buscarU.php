<?php

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "Robles38,", "pelimarket");

// Verificar si hay errores en la conexión
if (mysqli_connect_errno()) {
    echo "Error al conectar a la base de datos: " . mysqli_connect_error();
    exit();
}

// Realizar la búsqueda
if (isset($_POST['buscar'])) {
    $busqueda = $_POST['busqueda'];
    $query = "SELECT * FROM usuarios WHERE nombre LIKE '%$busqueda%'";
    $resultado = mysqli_query($conexion, $query);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Búsqueda de usuarios y películas</title>
    <link rel="stylesheet" type="text/css" href="editarAD.css">
    <link rel="stylesheet" href="/Actividad/templates/navbar.css">
</head>
<body>
    <header class="header">
        <div class="logo">
        <img src="/Actividad/pelimarket.png">
        </div>
<nav>
    <ul class="nav-links">
        <li><a href="/Actividad/Administrador/indexAdmin.html">Inicio</a></li>
        <li><a href="/Actividad/Administrador/Editar/editarAdmin.html">Editar Película</a></li>
        <li><a href="/Actividad/Administrador/Eliminar/eliminarAdmin.html">Eliminar Película</a></li>
        <li><a href="/Actividad/Administrador/Buscar/Cbuscar.php">Buscar</a></li>
    </ul>
</nav>
</header>
<br>
    <h1>Búsqueda de usuarios y películas</h1>
<br>
    <form method="POST">
        <label>Buscar:</label>
        <input type="text" name="busqueda">
        <input type="submit" name="buscar" value="Buscar">
    </form>

    <?php
    // Imprimir los resultados de la búsqueda en una tabla
    if (isset($resultado)) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Contraseña</th><th>Correo</th><th>telefono</th><th>Editar</th></tr>";
        while ($fila = mysqli_fetch_assoc($resultado)) {
            if (array_key_exists("nombre", $fila)) {
                // Imprimir resultados de usuarios
                echo "<tr>";
                foreach ($fila as $clave => $valor) {
                    if ($clave == "id") {
                        $usuario = $valor;
                        echo $fila['id'];
                    }
                    echo "<td>" . $valor . "</td>";
                }
                echo '<td><a href="/Actividad/Administrador/Editar/ModificarU.php?id=' . $fila['id'] . '">Editar</a></td>';
                echo "</tr>";
            }
        }
    }  
    ?>
</body>
</html>

