<?php


    /* Login */

$server = "localhost";
$username = "erick";
$password = "12345";
$database = "pelimarket";


$con = mysqli_connect($server, $username, $password, $database);

if(!$con){
    die("No hay conexion".mysqli_connect_error());
}

//Variables para conectar

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

//Para la consulta que se va hacer
$query = mysqli_query($con, "SELECT * FROM usuarios WHERE correo = '".$correo."'  and contrasena = '".$contrasena."'");

$nr = mysqli_num_rows($query);

if($nr == 1){
    // Obtener el ID del usuario
    $fila = mysqli_fetch_assoc($query);
    $id_usuario = $fila['id'];
    
    // Guardar el ID del usuario en una variable de sesión
    session_start();
    $_SESSION['id'] = $id_usuario;
    
    // Redirigir al usuario a la página donde se necesita el ID del usuario en la URL
    header("location:../Peliculas/Productos.php");
} else {
    echo "No ingresó usuario o no existe, vuelva a intentar";
}
