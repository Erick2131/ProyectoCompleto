<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Peliculas/EstiloPeliculas2.css">
    <link rel="stylesheet" href="../templates/navbar.css">
    <link rel="stylesheet" href="../templates/estilos.css">
    <title>Favoritos</title>
</head>
<body>
<header class="header">
    <div class="logo">
      <img src="../pelimarket.png" alt="">
  </div>
  <nav>
    <ul class="nav-links">
      <li><a href="../Peliculas/Productos.php">Peliculas</a></li>
      <li><a href="../Peliculas/guardar_favoritos.php"></a></li>
    </ul>
  </nav>
  <a class="btn" href="../Peliculas/carrito/mostrar_carrito.php"><button>Carrito</button></a>
  </header>
  <p class="titulofav">Tus Peliculas</p>
<?php
$servidor = "localhost";
$usuario = "erick";
$password = "12345";
$base_de_datos = "pelimarket";

// Creamos la conexión a la base de datos
$conexion = mysqli_connect($servidor, $usuario, $password, $base_de_datos);

// Verificamos si la conexión fue exitosa
if (!$conexion) {
  die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

session_start();
$id_usuario = $_SESSION['id'];

// Consultar las películas compradas en el historial de productos
$query_compras = "SELECT h.id_peliculas, p.titulo, p.imagen 
                  FROM historial_productos h 
                  JOIN peliculas p ON h.id_peliculas = p.id_peliculas 
                  WHERE h.id_usuario = '$id_usuario'
                  GROUP BY h.id_peliculas";
$resultado_compras = mysqli_query($conexion, $query_compras);

// Verificar si se encontraron películas en el historial de compras
if ($resultado_compras && mysqli_num_rows($resultado_compras) > 0) {
  echo "<div class='movie-grid2'>";
  while ($fila_pelicula = mysqli_fetch_assoc($resultado_compras)) {
    echo "<div class='movie zoom'>";
    echo "<a href='../Mis peliculas/mispeliculasdetalles.php?id=" . $fila_pelicula['id_peliculas'] . "'>";
    echo "<div class='movie-image' style='background-image: url(" . $fila_pelicula['imagen'] . ");'></div>";
    echo "<div class='movie-info'>";
    echo "<h3 class='movie-title'>" . $fila_pelicula['titulo'] . "</h3>";
    echo "</div>";
    echo "</a>";
    echo "</div>";
  }
  echo "</div>";
} else {
  echo "<p class='txt'>No se encontraron películas en el historial de compras.</p>";
}


// Verificar si se encontraron películas en el historial de compras
if ($resultado_compras && mysqli_num_rows($resultado_compras) > 0) {
  echo "<div class='movie-grid2'>";
  while ($fila_pelicula = mysqli_fetch_assoc($resultado_compras)) {
    echo "<div class='movie zoom'>";
    echo "<a href='../Mis peliculas/mispeliculasdetalles.php?id=" . $fila_pelicula['id_peliculas'] . "'>";
    echo "<div class='movie-image' style='background-image: url(" . $fila_pelicula['imagen'] . ");'></div>";
    echo "<div class='movie-info'>";
    echo "<h3 class='movie-title'>" . $fila_pelicula['titulo'] . "</h3>";
    echo "</div>";
    echo "</a>";
    echo "</div>";
  }
  echo "</div>";
} else {
  echo "<p class='txt'>No se encontraron películas en el historial de compras.</p>";
}


?>
