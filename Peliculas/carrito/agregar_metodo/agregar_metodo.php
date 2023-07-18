<?php
$server = "localhost";
$username = "erick";
$password = "12345";
$database = "pelimarket";

$con = mysqli_connect($server, $username, $password, $database);

if (!$con) {
    die("No hay conexión: " . mysqli_connect_error());
}

session_start();
$id_usuario = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obtener los valores enviados por el formulario
  $mp = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : "";
  $numero_tarjeta = isset($_POST['numero_tarjeta']) ? $_POST['numero_tarjeta'] : "";

  // Validar el número de tarjeta
  if (preg_match("/^\d{16}$/", $numero_tarjeta)) {
      // El número de tarjeta tiene 16 dígitos, continuar con el procesamiento
      // Insertar la información en la tabla usuario_info
      $query = "INSERT INTO usuario_info (id_usuario, metodo_pago, numero_tarjeta) VALUES ('$id_usuario', '$mp', '$numero_tarjeta')";
      if (mysqli_query($con, $query)) {
          // Redirigir al usuario a mostrar_carrito.php
          header("Location: /Peliculas/carrito\mostrar_carrito.php");
          exit; // Agrega esta línea para detener la ejecución del script después de la redirección
      } else {
          echo "Error al guardar la información en la base de datos: " . mysqli_error($con);
      }
  } else {
      echo "El número de tarjeta ingresado no es válido. Asegúrese de ingresar exactamente 16 dígitos.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="LoginCSS.css">
    <link rel="stylesheet" href="/templates/navbar.css">
    <link rel="stylesheet" href="/Peliculas/carrito/agregar_metodo/agregarm.css">
    <title>Login</title>
</head>
<body>
  <header class="header">
    <div class="logo">
      <img src="../pelimarket.png" alt="">
    </div>
    <nav>
      <ul class="nav-links">
        <li><a href="../index.html">Home</a></li>
        <li><a href="../Peliculas/Productos.html">Peliculas</a></li>
        <li><a href="../Registro/Registro.html">Registrarse</a></li>
      </ul>
    </nav>
  </header>
  <form class="formulario" method="post">
    <h1 class="h1">Añadir Método de Pago</h1>
    <div class="contenedor">
      <div class="input-contenedor">
        <i class="fas fa-credit-card icon"></i>
        <select name="metodo_pago" id="metodo_pago">
          <option value="debito">Débito</option>
        </select>
      </div>
      <div class="input-contenedor">
        <i class="fas fa-credit-card icon"></i>
        <input type="text" placeholder="Número de Tarjeta (16 dígitos)" name="numero_tarjeta" id="numero_tarjeta" pattern="\d{16}" required>
      </div>
      <button type="submit" class="button">
        <p>Agregar</p>
      </button>
    </div>
  </form>
</body>
</html>

