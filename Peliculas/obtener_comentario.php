<?php
session_start();
$id = $_GET['id'];


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
// Verificamos si la conexión fue exitosa
if (!$conexion) {
  die("Error al conectar a la base de datos: " . mysqli_connect_error());
}
// Obtener los comentarios de la película
$query_comentarios = "SELECT c.comentario, c.fecha, u.nombre FROM comentario c INNER JOIN usuarios u ON c.id_usuario = u.id WHERE c.id_peliculas = $id";

$resultado_comentarios = mysqli_query($conexion, $query_comentarios);

$comentarios = array();

if (mysqli_num_rows($resultado_comentarios) > 0) {
  while ($comentario = mysqli_fetch_assoc($resultado_comentarios)) {
    $comentarios[] = array(
      'nombre' => $comentario['nombre'],
      'fecha' => $comentario['fecha'],
      'contenido' => $comentario['comentario']
    );
  }
}

// Devolver los comentarios como respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($comentarios);
?>
