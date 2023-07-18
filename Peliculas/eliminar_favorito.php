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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_peliculas'])) {
  // Obtener el ID de la película a eliminar
  $id_peliculas = $_POST['id_peliculas'];

  // Eliminar la película de la tabla de favoritos
  $query = "DELETE FROM favoritos WHERE id_usuario = '$id_usuario' AND id_peliculas = '$id_peliculas'";
  $resultado = mysqli_query($conexion, $query);

  if ($resultado) {
    // La eliminación fue exitosa
    // Puedes enviar una respuesta JSON al cliente indicando el éxito de la eliminación,
    // o simplemente enviar una respuesta de texto plano con un mensaje.
    echo "Película eliminada de favoritos.";
  } else {
    // Ocurrió un error durante la eliminación, mostrar un mensaje de error o realizar
    // alguna acción de manejo de errores.
    echo "Error al eliminar la película de favoritos: " . mysqli_error($conexion);
  }
}

mysqli_close($conexion);
?>
