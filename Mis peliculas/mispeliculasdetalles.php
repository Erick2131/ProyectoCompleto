<?php
// Aquí ponemos los datos de conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$password = "Robles38,";
$base_de_datos = "pelimarket";

// Creamos la conexión a la base de datos
$conexion = mysqli_connect($servidor, $usuario, $password, $base_de_datos);

// Verificamos si la conexión fue exitosa
if (!$conexion) {
  die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

$id = mysqli_real_escape_string($conexion, $_GET['id']);

$sql = "SELECT * FROM peliculas WHERE id_peliculas = $id";
$resultado = mysqli_query($conexion, $sql);
$fila = mysqli_fetch_assoc($resultado);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtener el ID del usuario actual desde la sesión
  session_start();
  $id_usuario = $_SESSION['id'];

  // Obtener los datos del producto del formulario
  $id_peliculas = $_POST['id_peliculas'];
  $modalidad = $_POST['modalidad'];
  $precio = 0;

  // Determinar el precio según la modalidad
  if ($modalidad === 'compra') {
    $precio = $fila['precio'];
  } else if ($modalidad === 'renta') {
    $precio = $fila['renta'];
  }

  // Insertar el producto en la tabla de carrito
  $query = "INSERT INTO carrito (id_usuario, id_peliculas, modalidad, precio) VALUES ('$id_usuario', '$id_peliculas', '$modalidad', '$precio')";

  if (mysqli_query($conexion, $query)) {
    // El producto se agregó al carrito correctamente
    header("Location: /Actividad/Peliculas/carrito/mostrar_carrito.php"); // Redireccionar al carrito
    exit(); // Asegurarse de que no se ejecute más código después de la redirección
  } else {
    // Hubo un error al agregar el producto al carrito
    echo "Hubo un error al agregar el producto al carrito: " . mysqli_error($conexion);
  }
 
// Obtener la calificación de la película
$query_calificacion = "SELECT * FROM calificacion WHERE id_pelicula = $id";
$resultado_calificacion = mysqli_query($conexion, $query_calificacion);
$calificacion = mysqli_fetch_assoc($resultado_calificacion);

// Obtener la cantidad de "Me gusta" y "No me gusta"
$likes = $calificacion['likes'];
$dislikes = $calificacion['dislikes'];

  // Cerrar la conexión a la base de datos
  mysqli_close($conexion);

  
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/Actividad/Mis peliculas/mispeliculas-detalles.css">
  <link rel="stylesheet" href="/Actividad/templates/navbar.css">
  <link rel="stylesheet" href="/Actividad\fontawesome/css/all.css">
  <title><?php echo $fila['titulo']; ?></title>
</head>

<body>
<header class="header">
    <div class="logo">
      <img src="/Actividad/pelimarket.png" alt="">
  </div>
  <nav>
    <ul class="nav-links">
      <li><a href="/Actividad/index.html">Home</a></li>
      <li><a href="/Actividad/Registro/Registro.html">Registrarse</a></li>
      <li><a href="/Actividad/Peliculas/carrito/mostrar_carrito.php">Carrito</a></li>
    </ul>
  </nav>
  </header>
  <div class="movie-details">
    <div class="movie-details-img">
      <img src="<?php echo $fila['imagen']; ?>">
    </div>
    <div class="movie-info">
      <h2><?php echo $fila['titulo']; ?></h2>
      <!------------------------------------------------------------movie rating--------------->
<form method="POST" action="/Actividad/Peliculas/guardar_favoritos.php">
  <button class="favorite-button separation" name="id_peliculas" value="<?php echo $fila['id_peliculas']; ?>">
    <i class="fas fa-thin fa-heart fa-bounce" style="color: #bc2fc6;"></i>
  </button>
</form>
    </div>
    <!-------------------------------------------------------------------------------------------->
    <form>
        <input class="btn1" type="submit" value="Ver ahora">
      </form>
    </div>
  </div>
  <div class="movie-info-container">
  <div class="movie-info-details">
    <h3 class="extra">Información completa de la película</h3>
    <p>Título: <?php echo $fila['titulo']; ?></p>
    <p>Género: <?php echo $fila['genero']; ?></p>
    <p>Fecha Estreno: <?php echo $fila['fecha']; ?></p>
    <p>Sinopsis: <?php echo $fila['descripcion']; ?></p>
    <!-- Agrega más detalles de la película según tus necesidades -->
  </div>

  <div class="movie-comments-container">
  <h3 class="extra">Comentarios de la película</h3>
  <div id="comentarios-container"></div>
  <br><br>
  <h3 class="extra">Agregar comentario</h3>
  <form id="comentario-form">
    <textarea name="comentario" placeholder="Escribe tu comentario aquí" required></textarea><br>
    <input class="btn1" type="submit" value="Enviar comentario">
  </form>
</div>

<script>
  // Obtener el contenedor de comentarios
 // Obtener el contenedor de comentarios
// Obtener el contenedor de comentarios
const comentariosContainer = document.getElementById('comentarios-container');

// Función para mostrar los comentarios de forma secuencial
function mostrarComentariosSecuencialmente(comentarios, indice) {
  if (indice >= comentarios.length) {
    return; // Se han mostrado todos los comentarios, salir de la función
  }

  const { nombre, fecha, contenido } = comentarios[indice];

  // Crear un nuevo elemento de comentario
  const nuevoComentario = document.createElement('div');
  nuevoComentario.classList.add('comentario', 'comentario-dinamico');

  // Asignar el contenido del comentario
  nuevoComentario.innerHTML = `
    <p class="usuario">${nombre}</p>
    <p class="fecha">${fecha}</p>
    <p class="contenido">${contenido}</p>
  `;

  // Mostrar el comentario y aplicar la animación
  comentariosContainer.appendChild(nuevoComentario);
  nuevoComentario.style.opacity = '1';

  // Ocultar el comentario después de 6 segundos
  setTimeout(() => {
    nuevoComentario.style.opacity = '0';
    // Eliminar el comentario del DOM después de ocultarlo
    setTimeout(() => {
      comentariosContainer.removeChild(nuevoComentario);
      // Llamar recursivamente para mostrar el siguiente comentario
      mostrarComentariosSecuencialmente(comentarios, indice + 1);
    }, 1000); // Esperar 1 segundo antes de eliminar el comentario del DOM
  }, 6000); // Ocultar cada comentario después de 6 segundos
}

// Función para obtener los comentarios de la película
function obtenerComentarios() {
  // Realizar la solicitud AJAX al servidor
  const xhr = new XMLHttpRequest();
  xhr.open('GET', 'obtener_comentario.php?id=<?php echo $id; ?>', true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      // Obtener la respuesta del servidor
      const comentarios = JSON.parse(xhr.responseText);

      // Mostrar los comentarios secuencialmente
      mostrarComentariosSecuencialmente(comentarios, 0);
    }
  };
  xhr.send();
}

// Obtener los comentarios cuando se carga la página
obtenerComentarios();


  // Enviar un nuevo comentario
  const comentarioForm = document.getElementById('comentario-form');
  comentarioForm.addEventListener('submit', function (e) {
    e.preventDefault();

    // Obtener el contenido del comentario
    const comentarioTextarea = comentarioForm.querySelector('textarea');
    const contenidoComentario = comentarioTextarea.value;

    // Realizar la solicitud AJAX para guardar el comentario
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'guardar_comentario.php?id=<?php echo $id; ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
      if (xhr.status === 200) {
        // Limpiar el formulario y obtener los nuevos comentarios
        comentarioTextarea.value = '';
        obtenerComentarios();
      }
    };
    xhr.send('comentario=' + encodeURIComponent(contenidoComentario));
  });
</script>

<script>
  // Obtén todos los comentarios dinámicos
  const comentariosDinamicos = document.querySelectorAll('.comentario-dinamico');

  // Mostrar el primer comentario y aplicar la animación
  comentariosDinamicos[0].style.display = 'block';
  comentariosDinamicos[0].style.animation = 'desvanecer 6s ease-in-out infinite';

  comentariosDinamicos[0].addEventListener('animationend', () => {
    // Oculta el comentario actual
    comentariosDinamicos[0].style.display = 'none';

    // Muestra el siguiente comentario
    const siguienteComentario = comentariosDinamicos[1 % comentariosDinamicos.length];
    siguienteComentario.style.display = 'block';
    siguienteComentario.style.animation = 'desvanecer 6s ease-in-out infinite';
  });
</script>



</body>
</html>
