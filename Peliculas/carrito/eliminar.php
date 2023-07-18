<?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "erick", "12345", "pelimarket");

// Verificar si hay errores en la conexión
if (mysqli_connect_errno()) {
    echo "Error al conectar a la base de datos: " . mysqli_connect_error();
    exit();
}

// Verificar si se recibió el ID de la compra a eliminar
if (isset($_POST['id_compra'])) {
    $id_compra = $_POST['id_compra'];

    // Consulta para eliminar la película del carrito
    $query = "DELETE FROM carrito WHERE id_compra = '$id_compra'";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        echo "Película eliminada correctamente del carrito.";
    } else {
        echo "Error al eliminar la película del carrito: " . mysqli_error($conexion);
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>



