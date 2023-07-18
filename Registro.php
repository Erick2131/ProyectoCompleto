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

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$telefono = $_POST['telefono'];

$sql="INSERT INTO usuarios (id, nombre, apellido, correo, contrasena,telefono)  values (0, '$nombre','$apellido', '$correo', '$contrasena','$telefono')";

if($conexion){
    header("location:../index.html");
}else{
    echo "Error al insertar datos: " . mysqli_error($sql);
}

?>





