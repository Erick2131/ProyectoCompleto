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
$query = mysqli_query($con, "SELECT * FROM administradores WHERE correo = '".$correo."'  and contrasena = '".$contrasena."'");

$nr = mysqli_num_rows($query);

if($nr == 1){
    header("location:../Administrador/indexAdmin.html");
    //echo "Bienvenido" .$correo;
}
else if($nr == 0)
    echo "No ingreso usuario o no existe, vuelva a intentar";
?>
