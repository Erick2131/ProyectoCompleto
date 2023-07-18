<?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "Robles38,", "pelimarket");

// Verificar si hay errores en la conexión
if (mysqli_connect_errno()) {
    echo "Error al conectar a la base de datos: " . mysqli_connect_error();
    exit();
}

// Realizar la búsqueda de usuarios
if (isset($_POST['buscar_usuarios'])) {
    $busqueda = $_POST['busqueda_usuarios'];
    $query = "SELECT * FROM usuarios WHERE nombre LIKE '%$busqueda%'";
    $resultado_usuarios = mysqli_query($conexion, $query);
}

// Realizar la búsqueda de películas
if (isset($_POST['buscar_peliculas'])) {
    $busqueda = $_POST['busqueda_peliculas'];
    $query = "SELECT * FROM peliculas WHERE titulo LIKE '%$busqueda%'";
    $resultado_peliculas = mysqli_query($conexion, $query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Búsqueda de usuarios y películas</title>
    <link rel="stylesheet" type="text/css" href="buscarAD_CSS.css">
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
        <li><a href="/Actividad/Administrador/Editar/indexEditar.html">Editar Película</a></li>
        <li><a href="/Actividad/Administrador/Eliminar/indexEliminar.html">Eliminar Película</a></li>
    </ul>
</nav>
</header>
<h1>Búsqueda de usuarios y películas</h1>

<form class="formulario" method="POST">
    <div class="contenedor">
        <input type="text" name="busqueda_usuarios">
        <br></br><input type="submit" class="button" name="buscar_usuarios" value="Buscar usuarios">
    </div>
</form>

<div class="grid-container">
    <?php
    // Imprimir los resultados de la búsqueda de usuarios en un grid
    if (isset($resultado_usuarios)) {
        while ($fila = mysqli_fetch_assoc($resultado_usuarios)) {
            echo "<div class='usuario'>";
            foreach ($fila as $clave => $valor) {
                echo "<div><strong>" . $clave . ":</strong> " . $valor . "</div>";
            }
            echo "</div>";
        }
    }
    ?>
</div>

<form class="formulario" method="POST">
    <div class="contenedor">
        <input type="text" name="busqueda_peliculas"><br></br>
        <input type="submit" class="button" name="buscar_peliculas" value="Buscar películas">
    </div>
</form>

<div class="grid-container">
    <?php
    // Imprimir los resultados de la búsqueda de películas en un grid
    if (isset($resultado_peliculas)) {
        while ($fila = mysqli_fetch_assoc($resultado_peliculas)) {
            echo "<div class='pelicula'>";
            foreach ($fila as $clave => $valor) {
                echo "<div><strong>" . $clave . ":</strong> " . $valor . "</div>";
            }
            echo "</div>";
        }
    }
    ?>
</div>
</body>
</html>
