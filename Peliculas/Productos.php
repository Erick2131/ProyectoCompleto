<?php
session_start();
$id_usuario = $_SESSION['id'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Productos</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../Peliculas/EstiloPeliculas.css">
  <link rel="stylesheet" href="../templates/navbar.css">
  <link rel="stylesheet" href="../templates/estilos.css">
</head>
<body>
  <header class="header">
    <div class="logo">
      <img src="../pelimarket.png" alt="">
  </div>
  <nav>
    <ul class="nav-links">
      <li><a href="../index.html">Home</a></li>
      <li><a href="../Registro/Registro.html">Registrarse</a></li>
      <li><a href="../Peliculas/guardar_favoritos.php?id=<?php echo $id_usuario; ?>">Favoritos</a></li>
      <li><a href="../Mis peliculas/mispeliculas.php">Mis peliculas</a></li>
    </ul>
  </nav>
  <a class="btn" href="../Peliculas/carrito/mostrar_carrito.php"><button>Carrito</button></a>
  </header>
	<h1>Productos</h1>
	<?php
  // Aquí llamamos al archivo que hace la conexión a la base de datos
  require_once('../Peliculas/Conexion.php');

  // Aquí creamos la consulta SQL para obtener las películas
  $sql = "SELECT * FROM peliculas";

  // Aquí ejecutamos la consulta
  $resultado = mysqli_query($conexion, $sql);

  // Si hay resultados, los mostramos en un grid de películas
  if (mysqli_num_rows($resultado) > 0) {
    echo "<div class='movie-grid'>";
    while ($fila = mysqli_fetch_assoc($resultado)) {
      echo "<div class='movie zoom'>";
      // Aquí mostramos la imagen usando la URL que está en la base de datos
      echo "<a href='../Peliculas/pelicula-detalle.php?id=" . $fila['id_peliculas'] . "'>";
      echo "<div class='movie-image' style='background-image: url(" . $fila['imagen'] . ");'></div>";
      echo "<div class='movie-info'>";
      echo "<h3 class='movie-title'>" . $fila['titulo'] . "</h3>";
      echo "<p class='movie-price'>$" . $fila['precio'] . "</p>";
      echo "</div>";
      echo "</div>";
      echo "</a>";
    }
    echo "</div>";
  } else {
    echo "No se encontraron películas.";
  }

  // Aquí cerramos la conexión a la base de datos
  mysqli_close($conexion);
?>

</div>
</body>
<script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
<footer class="pie-pagina">
  <div class="grupo-1">
      <div class="box">
          <figure>
              <a href="#">
                  <img src="../pelimarket.png" alt="Logo de SLee Dw">
              </a>
          </figure>
      </div>
      <div class="box">
          <h2>SOBRE NOSOTROS</h2>
          <p>Bienvenido a nuestra página de películas, donde podrás encontrar los estrenos más recientes a un precio barato para rentar o comprar.</p>
          
      </div>
      <div class="box">
          <h2>SIGUENOS</h2>
          <div class="red-social">
              <a href="#" class="fa fa-facebook"></a>
              <a href="#" class="fa fa-instagram"></a>
              <a href="#" class="fa fa-twitter"></a>
              <a href="#" class="fa fa-youtube"></a>
          </div>
      </div>
  </div>
  <div class="grupo-2">
      <small>&copy; 2023 <b>PeliMarket</b> - Todos los Derechos Reservados.</small>
  </div>
</footer>
</html>
