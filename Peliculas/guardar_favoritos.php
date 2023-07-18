<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Peliculas/EstiloPeliculas2.css">
    <link rel="stylesheet" href="../templates/navbar.css">
    <link rel="stylesheet" href="../templates\estilos.css">
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
  <p class="titulofav">Tus favoritos</p>
<?php
// Aquí ponemos los datos de conexión a la base de datos
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtener el ID de la película
  $id_peliculas = $_POST['id_peliculas'];

  // Verificar si la película ya ha sido agregada a favoritos por el usuario
  $query_verificar = "SELECT * FROM favoritos WHERE id_usuario = '$id_usuario' AND id_peliculas = '$id_peliculas'";
  $resultado_verificar = mysqli_query($conexion, $query_verificar);

  if ($resultado_verificar && mysqli_num_rows($resultado_verificar) > 0) {
    // La película ya ha sido agregada a favoritos, mostrar el mensaje correspondiente
    echo "<div class='movie-comments-container'>";
    echo "<p class='txt'>La película ya ha sido agregada con anterioridad</p>";
    echo "<p class='txt'>Puede consultar sus peliculas favoritas dando clic en peliculas>favoritos</p>";
    echo"</div>";
  } else {
    // Insertar el favorito en la tabla de favoritos
    $query = "INSERT INTO favoritos (id_usuario, id_peliculas) VALUES ('$id_usuario', '$id_peliculas')";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
      // La inserción fue exitosa
      echo "<p class='txt'>Película agregada a favoritos.</p>";
      echo "<p class='txt'>Puede consultar sus peliculas favoritas dando clic en peliculas>favoritos</p>";
    } else {
      // Ocurrió un error durante la inserción, mostrar un mensaje de error o realizar
      // alguna acción de manejo de errores.
      echo "Error al agregar la película a favoritos: " . mysqli_error($conexion);
    }
  }
} else {
  // No se especificó ningún método de solicitud (GET o POST), mostrar todas las películas favoritas del usuario
  $query_favoritos = "SELECT p.id_peliculas, p.titulo, p.precio, p.imagen FROM favoritos f JOIN peliculas p ON f.id_peliculas = p.id_peliculas WHERE f.id_usuario = '$id_usuario'";
  $resultado_favoritos = mysqli_query($conexion, $query_favoritos);

  // Verificar si se encontraron películas en favoritos
  if ($resultado_favoritos && mysqli_num_rows($resultado_favoritos) > 0) {
    echo "<div class='movie-grid2'>";
    while ($fila_pelicula = mysqli_fetch_assoc($resultado_favoritos)) {
      echo "<div class='movie zoom'>";
      echo "<a href='../Peliculas/pelicula-detalle.php?id=" . $fila_pelicula['id_peliculas'] . "'>";
      echo "<div class='movie-image' style='background-image: url(" . $fila_pelicula['imagen'] . ");'></div>";
      echo "<div class='movie-info'>";
      echo "<h3 class='movie-title'>" . $fila_pelicula['titulo'] . "</h3>";
      echo "</div>";
      echo "<a href='#' class='remove-favorite' data-id='" . $fila_pelicula['id_peliculas'] . "'>Eliminar de favoritos</a>";
      echo "</div>";
      echo "</a>";
    }
    echo "</div>";
  } else {
    echo "<p class='txt'>No se encontraron películas en favoritos.</p>";
  }
}

mysqli_close($conexion);
?>
<script>
    // Obtén todos los elementos con la clase 'remove-favorite'
    var removeLinks = document.getElementsByClassName('remove-favorite');

    // Recorre todos los elementos y agrega un evento de clic a cada uno
    for (var i = 0; i < removeLinks.length; i++) {
      removeLinks[i].addEventListener('click', function(event) {
        event.preventDefault(); // Evita que el enlace realice la acción por defecto (navegar a una URL)

        // Obtén el ID de la película desde el atributo 'data-id' del enlace
        var peliculaId = this.getAttribute('data-id');

        // Guarda una referencia al elemento 'movie' que contiene la película a eliminar
        var movieElement = this.parentNode;

        // Realiza una solicitud AJAX para eliminar la película de favoritos
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../Peliculas/eliminar_favorito.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              // Elimina el elemento HTML de la lista de favoritos
              movieElement.parentNode.removeChild(movieElement);

              // Recarga la página después de eliminar la película
              location.reload();
            } else {
              // Manejo de errores en caso de fallo en la solicitud AJAX
              console.error('Error al eliminar la película de favoritos:', xhr.status);
            }
          }
        };

        xhr.send('id_peliculas=' + peliculaId);
      });
    }
  </script>
  <script>
    // Obtén todos los elementos con la clase 'remove-favorite'
    var removeLinks = document.getElementsByClassName('remove-favorite2');

    // Recorre todos los elementos y agrega un evento de clic a cada uno
    for (var i = 0; i < removeLinks.length; i++) {
      removeLinks[i].addEventListener('click', function(event) {
        event.preventDefault(); // Evita que el enlace realice la acción por defecto (navegar a una URL)

        // Obtén el ID de la película desde el atributo 'data-id' del enlace
        var peliculaId = this.getAttribute('data-id');

        // Guarda una referencia al elemento 'movie' que contiene la película a eliminar
        var movieElement = this.parentNode;

        // Realiza una solicitud AJAX para eliminar la película de favoritos
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../Peliculas/eliminar_favorito.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              // Elimina el elemento HTML de la lista de favoritos
              movieElement.parentNode.removeChild(movieElement);

              // Recarga la página después de eliminar la película
              location.reload();
            } else {
              // Manejo de errores en caso de fallo en la solicitud AJAX
              console.error('Error al eliminar la película de favoritos:', xhr.status);
            }
          }
        };

        xhr.send('id_peliculas=' + peliculaId);
      });
    }
  </script>
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
</HTML>


