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


// Obtener la información del usuario a editar
$query = "SELECT * FROM usuarios WHERE id='$id'";

$resultado = mysqli_query($conexion, $query);
$usuario = mysqli_fetch_assoc($resultado);

// Verificar si la película existe
if (!$usuario) {
    echo "El usuario que intentas editar no existe.";
    exit();
}

// Actualizar la información de la película
if (isset($_POST['editar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $contrasena = $_POST['contrasena'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $query = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', contrasena='$contrasena', correo='$correo', telefono='$telefono' WHERE id='$id'";
    $resultado = mysqli_query($conexion, $query);
    $resultado = mysqli_query($conexion, $query);
    if (!$resultado) {
        echo "Error al editar el usuario: " . mysqli_error($conexion);
    } else {
        echo "El usuario ha sido editada correctamente.";
        $resultado = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id='$id'");
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
            <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>"><br>
        </div>
        <div class="input-contenedor">    
            <input type="text" name="apellido" value="<?php echo $usuario['apellido']; ?>"><br>
        </div>
        <div class="input-contenedor">    
            <input type="password" name="contrasena" value="<?php echo $usuario['contrasena']; ?>"><br>
        </div>
        <div class="input-contenedor">    
            <input type="text" name="correo" value="<?php echo $usuario['correo']; ?>"><br>
        </div>
        <div class="input-contenedor">
            <input type="text" name="telefono" value="<?php echo $usuario['telefono']; ?>"><br>
        </div>
        <button type="submit" name="editar" value="Editar">Editar</button>
    </form>
</body>
</html>