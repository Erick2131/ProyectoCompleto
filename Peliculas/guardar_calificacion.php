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

$id = mysqli_real_escape_string($conexion, $_GET['id']);

$query_calificacion = "SELECT cal FROM calificacion WHERE id_peliculas = $id";
$resultado_calificacion = mysqli_query($conexion, $query_calificacion);

if (mysqli_num_rows($resultado_calificacion) > 0) {
  // La calificación ya existe, obtener el valor actual
  $row = mysqli_fetch_assoc($resultado_calificacion);
  $calificacion_actual = $row['cal'];
} else {
  // La calificación no existe, establecer un valor inicial
  $calificacion_actual = '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si se envió una nueva calificación desde el formulario
    if (isset($_POST['cal'])) {
      $nueva_calificacion = $_POST['cal'];
  
      // Actualizar o insertar la calificación en la tabla
      if (mysqli_num_rows($resultado_calificacion) > 0) {
        // La calificación ya existe, actualizar el valor
        $query_actualizar_calificacion = "UPDATE calificacion SET cal = '$nueva_calificacion' WHERE id_peliculas = $id";
        mysqli_query($conexion, $query_actualizar_calificacion);
      } else {
        // La calificación no existe, insertar una nueva fila
        $query_insertar_calificacion = "INSERT INTO calificacion (id_peliculas, id_usuario, cal) VALUES ($id, $id_usuario, '$nueva_calificacion')";
        mysqli_query($conexion, $query_insertar_calificacion);
      }
  
      // Actualizar la calificación actual
      $calificacion_actual = $nueva_calificacion;
  
      // Redirigir al usuario a la página de la película
      header("Location:../Peliculas/pelicula-detalle.php?id=$id");
      exit; // Terminar el script para evitar ejecución adicional
    }
  }
  

mysqli_close($conexion);
?>
