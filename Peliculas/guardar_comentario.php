<?php
session_start();
$id_usuario = $_SESSION['id'];

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtener el ID de la película desde el parámetro de la URL
  $id_pelicula = $_GET['id'];

  // Obtener el comentario del formulario
  $comentario = mysqli_real_escape_string($conexion, $_POST['comentario']);

  // Insertar el comentario en la tabla
  $query_insertar_comentario = "INSERT INTO comentario (comentario, fecha, id_usuario, id_peliculas) VALUES ('$comentario', NOW(), $id_usuario, $id_pelicula)";

  if (mysqli_query($conexion, $query_insertar_comentario)) {
    // El comentario se agregó correctamente
    header("Location: ../Peliculas/pelicula-detalle.php?id=$id_pelicula"); // Redireccionar a la página de la película
    exit(); // Asegurarse de que no se ejecute más código después de la redirección
  } else {
    // Hubo un error al agregar el comentario
    echo "Hubo un error al agregar el comentario: " . mysqli_error($conexion);
  }
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
