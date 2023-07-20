
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/templates/navbar.css">
    <link rel="stylesheet" href="../carrito/disenocarrito.css">
</head>
<body>
<header class="header">
    <div class="logo">
      <img src="../pelimarket.png" alt="">
  </div>
  <nav>
    <ul class="nav-links">
      <li><a href="../Productos.php">Atras</a></li>
    </ul>
  </nav>
  </header>
        <?php
session_start();
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "erick", "12345", "pelimarket");

// Verificar si hay errores en la conexión
if (mysqli_connect_errno()) {
    echo "Error al conectar a la base de datos: " . mysqli_connect_error();
    exit();
}

// Obtener el ID del usuario actualmente autenticado
$id_usuario = $_SESSION['id']; // Reemplaza esto con tu lógica para obtener el ID del usuario

// Consulta para obtener la información del carrito del usuario actual
$query = "SELECT carrito.id_compra, carrito.id_peliculas, peliculas.titulo, carrito.precio
        FROM carrito
        INNER JOIN peliculas ON carrito.id_peliculas = peliculas.id_peliculas
        WHERE carrito.id_usuario = $id_usuario";
   

$resultado = mysqli_query($conexion, $query);
$query_payment_methods = "SELECT u.nombre,ui.id_mp, ui.metodo_pago, ui.numero_tarjeta FROM usuario_info ui INNER JOIN usuarios u ON ui.id_usuario = u.id WHERE ui.id_usuario = $id_usuario";
$result_payment_methods = mysqli_query($conexion, $query_payment_methods);

//suma

$query = "SELECT SUM(precio) AS total FROM carrito WHERE id_usuario = '$id_usuario'";
$result = mysqli_query($conexion, $query);



// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>

<div class="table-container">
  <?php
  if (mysqli_num_rows($resultado) > 0) {
    echo '<table class="minimal-table">';
    echo '<thead><tr><th>ID_compra</th><th>ID_pelicula</th><th>Título</th><th>Precio</th><th>Acciones</th></tr></thead>';
    echo '<tbody>';
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
      echo '<tr>';
      echo '<td>' . $fila['id_compra'] . '</td>';
      echo '<td>' . $fila['id_peliculas'] . '</td>';
      echo '<td>' . $fila['titulo'] . '</td>';
      echo '<td>' . $fila['precio'] . '</td>';
      echo '<td><button onclick="eliminarPelicula(' . $fila['id_compra'] . ')" class="btn-eliminar">Eliminar</button></td>';
      echo '</tr>';
    }

    echo '</tbody></table>';
    
  } else {
    echo '<p>No se encontraron registros en el carrito.</p>';
  }
  
  ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Aquí incluye el código JavaScript
  function eliminarPelicula(idPelicula) {
    // ...
    // Realizar la llamada AJAX para eliminar la película del carrito
    // Puedes usar la función fetch() o la librería jQuery.ajax() para hacer la petición al servidor

    // Ejemplo de llamada AJAX utilizando jQuery.ajax():
    $.ajax({
      url: '../carrito/eliminar.php',
      method: 'POST',
      data: { id_compra: idPelicula },
      success: function (response) {
        // La película se eliminó correctamente, puedes recargar la página o actualizar el carrito de forma dinámica
        location.reload(); // Recargar la página
      },
      error: function (xhr, status, error) {
        console.error(error);
      }
    });
  }
</script>

</div>
    <!-- Contenido del lado derecho -->
    <div class="right-half">
    <h2>Información del usuario</h2>
    <form action="../carrito/finalizar_transaccion.php" method="POST">
  <?php
  // Realizar la consulta por id_usuario a la tabla carrito

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $total = $row['total'];

    echo '<div class="form-container">';
    echo'Aqui se muestra el total de tu compra:';
       // Mostrar el total en el formulario
       echo '<p>Total: $' . $total . '</p>';
      } else {
        echo '<p class="no-data">No se encontraron datos adicionales del usuario.</p>';
      }
    echo '<label for="metodo_pago" class="form-label">Seleccione el método de pago:</label>';
    echo '<select name="metodo_pago" id="metodo_pago" class="form-select">';
    
    // Populate the dropdown select with available payment methods
    while ($row_payment_method = mysqli_fetch_assoc($result_payment_methods)) {
      $nombre_usuario = $row_payment_method['nombre'];
      $metodo_pago2 = $row_payment_method['id_mp'];
      $metodo_pago = $row_payment_method['metodo_pago'];
      $numero_tarjeta = $row_payment_method['numero_tarjeta'];
      echo '<option value="' . $metodo_pago2 . '">' . $nombre_usuario . ' - ' . $metodo_pago. ' - ' . $numero_tarjeta . '</option>';
      
      $_SESSION['id_mp'] = $metodo_pago2; // Asignar el valor dentro del bucle while
  }
  
  
    echo '<input type="hidden" name="id_metodopago" value="' . $_SESSION['id_mp'] . '">';
    echo '</div>';
    $_SESSION['total_compra'] = $total;
   
  ?>
   <button type="submit" value="finalizar" class="btn1">Finalizar</button>
</form>
<a href="../carrito/agregar_metodo/agregar_metodo.php"><button class="btn1" formaction="\Actividad\Peliculas\carrito\agregar_metodo\agregar_metodo.php">Agregar Método de pago</button></a>
  </div>
</div>

</body>
</html>
