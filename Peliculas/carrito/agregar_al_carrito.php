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

// Verificar que el usuario ha iniciado sesión
session_start();
if (!isset($_SESSION['id'])) {
	// Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
	header("Location: ../Login/Login.html");
	exit;
}

function buscar_producto($id) {
    if(!isset($_SESSION['carrito'])) {
        return false;
    }
    foreach($_SESSION['carrito'] as $posicion => $producto) {
        if($producto['id'] == $id) {
            return $posicion;
        }
    }
    return false;
}

// Obtener el ID del usuario actual desde la sesión
$id_usuario = $_SESSION['id'];
$id_peliculas = mysqli_real_escape_string($conexion, $_POST['id_peliculas']);

// Obtener los datos del producto seleccionado
if (isset($_POST['id_peliculas'])) {
	$id_peliculas = $_POST['id_peliculas'];
} else {
	die("No se especificó una película para agregar al carrito.");
}

$precio = 10; // Precio predeterminado para todas las películas

// Agregar la película al carrito
if (isset($_POST['agregar_al_carrito'])) {
	// Verificamos si el carrito no ha sido creado en la sesión
    if(!isset($_SESSION['carrito'])){
        $producto = array(
            'id' => $id_peliculas,
            'precio' => $precio,
        );
        $_SESSION['carrito'][0] = $producto;
    }
    else{
        $posicion = buscar_producto($id_peliculas);
        if($posicion !== false){
            $_SESSION['carrito'][$posicion]['modalidad'];
        }
        else{
            $posicion = count($_SESSION['carrito']);
            $producto = array(
                'id' => $id_peliculas,
                'precio' => $precio,
            );
            $_SESSION['carrito'][$posicion] = $producto;
        }
    }
    header("Location: ../Peliculas/Productos.php");
}

if (isset($_POST['modalidad'])) {
    $modalidad = $_POST['modalidad'];
} else {
    $modalidad = 'compra'; // establecer un valor predeterminado
}


// Obtener la información de la película desde la tabla de películas
$query = "SELECT * FROM peliculas WHERE id_peliculas = '$id_peliculas'";
$resultado = mysqli_query($conexion, $query);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
	die("La película especificada no se encontró en la base de datos.");
}

$datos_pelicula = mysqli_fetch_assoc($resultado);

// Obtener la cantidad desde el formulario

print_r($_POST);

// Obtener el precio de la película según la modalidad de compra o renta

// Insertar el producto en la tabla de carrito
$query = "INSERT INTO carrito (id_usuario, id_peliculas, modalidad, precio) VALUES ('$id_usuario', '$id_peliculas', '$modalidad', '$precio')";

if (!mysqli_query($conexion, $query)) {
	die("Error al agregar el producto al carrito: " . mysqli_error($conexion));
}

// Redirigir al usuario al carrito de compras
header("Location: ../Peliculas/carrito/mostrar_carrito.php");
exit;
?>
  </body>
</html>
