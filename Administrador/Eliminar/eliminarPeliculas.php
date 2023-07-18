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
    $query = "SELECT *  FROM peliculas WHERE titulo LIKE '%$busqueda%'";
    $resultado = mysqli_query($conexion, $query);
}

if (isset($_POST["eliminar"])) {
    $tabla = $_POST["tabla"];
    $id = $_POST["id_peliculas"];

    // Comprobar si la tabla es 'usuarios' o 'peliculas'
    if ($tabla == "peliculas") {
        // Desactivar la restricción de clave externa
        $query_desactivar_fk = "SET FOREIGN_KEY_CHECKS = 0";
        mysqli_query($conexion, $query_desactivar_fk);

        // Eliminar película
        $query = "DELETE FROM peliculas WHERE id_peliculas = '$id'";
        mysqli_query($conexion, $query);
        echo "Película eliminada con éxito.";

        // Volver a activar la restricción de clave externa
        $query_activar_fk = "SET FOREIGN_KEY_CHECKS = 1";
        mysqli_query($conexion, $query_activar_fk);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Búsqueda de usuarios y películas</title>
	<link rel="stylesheet" type="text/css" href="/Actividad/Administrador/Editar/editarAD.css">
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
		<li><a href="/Actividad/Administrador/Editar/editarAdmin.php">Editar Película</a></li>
		<li><a href="/Actividad/Administrador/Eliminar/indexEliminar.html">Eliminar Película</a></li>
		<li><a href="/Actividad/Administrador/Buscar/Cbuscar.php">Buscar</a></li>
	</ul>
</nav>
	</header>
	<br><br><br><br><br><br>
	<h1>Búsqueda de usuarios y películas</h1>

	<form method="POST">
    <div class="contenedor">
		<input type="text" name="busqueda">
		<input type="submit" name="buscar" value="Buscar">
	</form>

	<div class="grid-container">
		<?php
        // Imprimir los resultados de la búsqueda en un grid
		if (isset($resultado)) {
			while ($fila = mysqli_fetch_assoc($resultado)) {
				if (array_key_exists("nombre", $fila)) {
					$tabla = "usuarios"; // Establecer el valor de la variable $tabla
					echo "<div class='usuario'>";
					// Resto del código para imprimir el usuario y el formulario de eliminación
					foreach ($fila as $clave => $valor) {
								echo "<div><strong>" . $clave . ":</strong> " . $valor . "</div>";
							}
							echo "<form method='POST'>
									<input type='hidden' name='tabla' value='usuarios'>
									<input type='hidden' name='id' value='" . $fila['id'] . "'>
									<input type='submit' name='eliminar' value='Eliminar'>
								  </form>";
							echo "</div>";
		
				} else if (array_key_exists("titulo", $fila)) {
					$tabla = "peliculas"; // Establecer el valor de la variable $tabla
					echo "<div class='pelicula'>";
					// Resto del código para imprimir la película y el formulario de eliminación
					foreach ($fila as $clave => $valor) {
								echo "<div><strong>" . $clave . ":</strong> " . $valor . "</div>";
							}
							echo "<form method='POST'>
									<input type='hidden' name='tabla' value='peliculas'>
									<input type='hidden' name='id_peliculas' value='" . $fila['id_peliculas'] . "'>
									<input type='submit' name='eliminar' value='Eliminar'>
								  </form>";
							echo "</div>";
				}
			}
		}
		
		
		?>

</body>
</html>