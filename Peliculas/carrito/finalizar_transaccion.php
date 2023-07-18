<?php
require('../fpdf/fpdf.php'); // Incluir la librería FPDF

function main() {
  session_start();
  // Obtener los datos enviados desde el formulario
  $metodo_pago2 = $_POST['metodo_pago'];

  // Obtener los productos seleccionados del formulario
  $productos = getSelectedProducts($_SESSION['id']); // Pasar el ID de usuario como parámetro

  // Realizar cálculos adicionales, como sumar el precio de los productos comprados
  $total_compra = $_SESSION['total_compra'];

  // Guardar la información en la tabla "historial"
  $id_usuario = $_SESSION['id']; // Obtener el ID del usuario actualmente autenticado

  $conexion = mysqli_connect("localhost", "erick", "12345", "pelimarket");

  if (mysqli_connect_errno()) {
    echo "Error al conectar a la base de datos: " . mysqli_connect_error();
    exit();
  }

  if (is_numeric($total_compra) && is_numeric($metodo_pago2)) {
    // Realizar la inserción en la tabla "historial"
    $query = "INSERT INTO historial (id_usuario, total_compra, fecha_compra, id_mp) VALUES ('$id_usuario', '$total_compra', NOW(), '$metodo_pago2')";

    if (mysqli_query($conexion, $query)) {
      // La inserción fue exitosa, puedes continuar con las siguientes acciones

      // Obtener el ID del historial recién insertado
      $id_historial = mysqli_insert_id($conexion);

      // Guardar los detalles de los productos en la tabla "historial_productos"
foreach ($productos as $producto) {
  $producto_id = $producto['id'];
  $precio = $producto['precio'];

  $query_detalle = "INSERT INTO historial_productos (id_historial, id_peliculas, precio, id_usuario) VALUES ('$id_historial', '$producto_id', '$precio', '$id_usuario')";

  mysqli_query($conexion, $query_detalle);
}

// Desactivar la restricción de clave externa
$query_desactivar_fk = "SET FOREIGN_KEY_CHECKS = 0";
mysqli_query($conexion, $query_desactivar_fk);

// Eliminar los productos del carrito
$query_eliminar_carrito = "DELETE FROM carrito WHERE id_usuario = '$id_usuario'";
mysqli_query($conexion, $query_eliminar_carrito);

// Volver a activar la restricción de clave externa
$query_activar_fk = "SET FOREIGN_KEY_CHECKS = 1";
mysqli_query($conexion, $query_activar_fk);
// Obtener el correo electrónico del usuario a partir de su ID
$query_usuario = "SELECT correo FROM usuarios WHERE id = '$id_usuario'";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$row_usuario = mysqli_fetch_assoc($resultado_usuario);
$correo_usuario = $row_usuario['correo'];

// Resto del código...

      // Crear el PDF con el resumen de la compra
      $pdf = new FPDF();
      $pdf->AddPage();
      $pdf->SetFont('Arial', 'B', 16);
      $pdf->Cell(0, 10, 'Resumen de la compra', 0, 1);
      $pdf->Ln(10);
      $pdf->SetFont('Arial', '', 12);
      $pdf->Cell(0, 10, 'Productos comprados:', 0, 1);
      foreach ($productos as $producto) {
        $pdf->Cell(0, 10, '- Producto ID: ' . $producto['id'] . ', Titulo: '. $producto['titulo']. ', Precio: ' . $producto['precio'], 0, 1);
      }
      $pdf->Ln(10);
      $pdf->Cell(0, 10, 'Total de compra: ' . $total_compra, 0, 1);

      // Guardar el PDF en el servidor
      $pdfPath = '../pdfs/resumen_compra.pdf';
      $pdf->Output($pdfPath, 'F');

      // Enviar el correo electrónico con el resumen de la compra en formato PDF
      $fromEmail = "erick556luna@gmail.com";
      $toEmail = $correo_usuario; 
      $subject = "Resumen de la compra";
      $message = "Adjunto encontrarás el resumen de tu compra.";

      $headers = "From: $fromEmail\r\n";
      $headers .= "Reply-To: $fromEmail\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";

      $attachment = chunk_split(base64_encode(file_get_contents($pdfPath)));
      $filename = "resumen_compra.pdf";

      $body = "--boundary\r\n";
      $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
      $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
      $body .= "$message\r\n";
      $body .= "--boundary\r\n";
      $body .= "Content-Type: application/pdf; name=\"$filename\"\r\n";
      $body .= "Content-Transfer-Encoding: base64\r\n";
      $body .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
      $body .= "$attachment\r\n";
      $body .= "--boundary--";

      if (mail($toEmail, $subject, $body, $headers)) {
        // Eliminar el archivo PDF después de enviar el correo
        unlink($pdfPath);

        // Redirigir al usuario a una página de confirmación o agradecimiento
        header("Location: ../Peliculas/confirmacion.php");
        exit();
      } else {
        echo "Error al enviar el correo electrónico.";
      }
    } else {
      echo "Error al registrar la transacción: " . mysqli_error($conexion);
    }
  } else {
    echo "Error: El total de la compra no es un valor numérico.";
  }

  // Cerrar la conexión a la base de datos
  mysqli_close($conexion);
}

// Función para obtener los productos seleccionados del formulario
function getSelectedProducts($id_usuario)
{
  // Conectarse a la base de datos
  $conexion = mysqli_connect("localhost", "erick", "12345", "pelimarket");

  if (mysqli_connect_errno()) {
    echo "Error al conectar a la base de datos: " . mysqli_connect_error();
    exit();
  }

  // Consultar los productos en la tabla "carrito" para el usuario actual
  $query_productos = "SELECT c.id_peliculas, p.titulo, c.precio FROM carrito c INNER JOIN peliculas p ON c.id_peliculas = p.id_peliculas WHERE c.id_usuario = '$id_usuario'";
  $resultado_productos = mysqli_query($conexion, $query_productos);

  $productos = [];

  // Obtener los detalles de los productos seleccionados
  while ($fila = mysqli_fetch_assoc($resultado_productos)) {
    $producto_id = $fila['id_peliculas'];
    $titulo = $fila['titulo'];
    $precio = $fila['precio'];

    // Guardar los detalles del producto en el arreglo
    $producto = [
      'id' => $producto_id,
      'titulo' => $titulo,
      'precio' => $precio
    ];

    $productos[] = $producto;
  }

  // Cerrar la conexión a la base de datos
  mysqli_close($conexion);

  // Retornar el arreglo de productos
  return $productos;
}

// Llamada a la función main para ejecutar el código
main();
