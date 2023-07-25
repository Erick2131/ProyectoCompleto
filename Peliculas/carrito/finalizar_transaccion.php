<?php
try{

require('../../fpdf/fpdf.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';
require '../../PHPMailer/src/Exception.php';

function main() {
  session_start();

  // Obtener los datos enviados desde el formulario
  $metodo_pago2 = $_POST['metodo_pago'];

  // Obtener los productos seleccionados del formulario
  $productos = getSelectedProducts($_SESSION['id']); // Pasar el ID de usuario como parámetro

  // Realizar cálculos adicionales, como sumar el precio de los productos comprados
  $total_compra = $_SESSION['total_compra'];

  // Guardar la información en la tabla "historial"
  $id_usuario = $_SESSION['id'];
  // Obtener el ID del usuario actualmente autenticado
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

      // Guardar el PDF en el servidor con un nombre único (fecha + correo del usuario)
      $pdfFileName = 'resumen_compra_' . date("Ymd_His") . '_' . $correo_usuario . '.pdf';
      $pdfPath = '../../pdfs/' . $pdfFileName;
      $pdf->Output($pdfPath, 'F');
	
	
      // Enviar el correo electrónico con el resumen de la compra en formato PDF
      $mail = new PHPMailer();
      try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'erick556luna@gmail.com';
        $mail->Password = 'jhtpzlydoampldan';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('erick556luna@gmail.com', 'Pelimarket');
        $mail->addAddress($correo_usuario, 'Receptor');

        $mail->addStringAttachment($pdfPath, $pdfFileName);

        $mail->isHTML(true);
        $mail->Subject = 'Resumen de la compra';
        $mail->Body = 'Adjunto encontrarás el resumen de tu compra.';

        $mail->send();

        // Eliminar el archivo PDF después de enviar el correo
        if (file_exists($pdfPath)) {
          unlink($pdfPath);
        }

        // Redirigir al usuario a una página de confirmación o agradecimiento
        header("Location: ../confirmacion.php");
        exit();
      } catch (Exception $e) {
        echo "Error al enviar el correo electrónico: " . $mail->ErrorInfo;
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

}catch (Exception $e) {
    echo "Excepción atrapada en la conexión a la base de datos: " . $e->getMessage();}
?>
