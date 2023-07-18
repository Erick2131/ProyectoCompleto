<?php

    /* Login */

    $server = "localhost";
    $username = "root";
    $password = "Robles38,";
    $database = "pelimarket";
    
    
    $con = mysqli_connect($server, $username, $password, $database);
    
    if(!$con){
        die("No hay conexion".mysqli_connect_error());
    }


$titulo = $_POST['titulo'];
$genero = $_POST['genero'];
$fecha = $_POST['fecha'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$renta = $_POST['renta'];
$imagen = $_POST['imagen'];


$sql=mysqli_query($con,"INSERT INTO peliculas (id_peliculas, titulo, genero, fecha, descripcion,precio,renta, imagen)
values (0, '$titulo','$genero', '$fecha', '$descripcion','$precio','$renta','$imagen');");

if($con){
    header("location:/Actividad/Administrador/Agregar/agregarAdmin.html");
}else{
    mysqli_close($con);

}

?>
