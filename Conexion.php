<?php
// Conexión a la base de datos en localhost
$host = "localhost";
$user = "erick";
$password = "12345";
$database = "pelimarket";

// Crea la conexión
$conn = mysqli_connect($host, $user, $password, $database);

// Verifica si hay errores de conexión
if (!$conn) {
    die("Error al conectarse a la base de datos: " . mysqli_connect_error());
}
else {
echo "Conexión exitosa a la base de datos!";
}
// Cierra la conexión

?>
