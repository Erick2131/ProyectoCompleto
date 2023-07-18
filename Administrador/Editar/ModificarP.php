<?php

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "Robles38,", "pelimarket");

// Verificar si hay errores en la conexión
if (mysqli_connect_errno()) {
    echo "Error al conectar a la base de datos: " . mysqli_connect_error();
    exit();
}


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    echo $id;
} else {
    
    exit();
}


// Obtener la información de la película a editar
$query = "SELECT * FROM peliculas WHERE id_peliculas='$id'";

$resultado = mysqli_query($conexion, $query);
$pelicula = mysqli_fetch_assoc($resultado);

// Verificar si la película existe
if (!$pelicula) {
    echo "La película que intentas editar no existe.";
    exit();
}

// Actualizar la información de la película
if (isset($_POST['editar'])) {
    $titulo = $_POST['titulo'];
    $genero = $_POST['genero'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $precio =$_POST['precio'];
    $renta =$_POST['renta'];
    $imagen = $_POST['imagen'];
    $query = "UPDATE peliculas SET titulo='$titulo', genero='$genero', fecha='$fecha', descripcion='$descripcion', precio='$precio',renta= $renta, imagen='$imagen' WHERE id_peliculas='$id'";
    $resultado = mysqli_query($conexion, $query);
    $resultado = mysqli_query($conexion, $query);
    if (!$resultado) {
        echo "Error al editar la película: " . mysqli_error($conexion);
    } else {
        echo "La película ha sido editada correctamente.";
        $resultado = mysqli_query($conexion, "SELECT * FROM peliculas WHERE id_peliculas='$id'");
        $pelicula = mysqli_fetch_assoc($resultado);
    }
    
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar película</title>
    <link rel="stylesheet" href="editarAD.css">
</head>
<body>
    <form class="formulario" method="POST">
        <h1>Editar película</h1>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="input-contenedor">
            <input type="text" name="titulo" value="<?php echo $pelicula['titulo']; ?>"><br>
        </div>
        <div class="input-contenedor">    
            <input type="text" name="genero" value="<?php echo $pelicula['genero']; ?>"><br>
        </div>
        <div class="input-contenedor">    
            <input type="date" name="fecha" class="fecha-input" value="<?php echo $pelicula['fecha']; ?>"><br>
        </div>
        <div class="input-contenedor">    
            <input type="text" name="descripcion" value="<?php echo $pelicula['descripcion']; ?>"><br>
        </div>
        <div class="input-contenedor">    
            <input type="text" name="precio" value="<?php echo $pelicula['precio']; ?>"><br>
        </div>
        <div class="input-contenedor">    
            <input type="text" name="renta" value="<?php echo $pelicula['renta']; ?>"><br>
        </div>
        <div class="input-contenedor">
            <input type="text" name="imagen" value="<?php echo $pelicula['imagen']; ?>"><br>
        </div>
        <button type="submit" name="editar" value="Editar">Editar</button>
    </form>
</body>
</html>