<?php
session_start();
$total_compra = $_SESSION['total_compra'];

// Realizar la conexión a la base de datos
$servername = "localhost";
$username = "erick";
$password = "12345"
$dbname = "pelimarket";

$con = mysqli_connect($servername, $username, $password, $dbname);
if (!$con) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}
// Realizar la conexión a la base de datos
$servername = "localhost";
$username = "erick";
$password = "12345"
$dbname = "pelimarket";

$con = mysqli_connect($servername, $username, $password, $dbname);
if (!$con) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

include '../php/Conexion.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require "../../vendor/autoload.php";
require('../../fpdf/fpdf.php');
require('../../PHPMailer/src/PHPMailer.php');
require('../../PHPMailer/src/SMTP.php');
require('../../PHPMailer/src/Exception.php');


$id_usuario = $_SESSION['id']; // Cambio de $_SESSION['id_usuario'] a $_SESSION['id']
$nombre = $_SESSION['nombre'];
$telefono = $_SESSION['telefono'];
$correo = $_SESSION['correo'];
$productos = json_decode($_SESSION["productos"]);
$producto = $productos[0]->nombre_producto;


$sql_carrito = "SELECT * FROM carrito WHERE id_usuario = $id_usuario";
$resultado_carrito = $con->query($sql_carrito);


// Crear un nuevo objeto FPDF
$pdf = new FPDF();


// Agregar una nueva página al PDF
$pdf->AddPage();


// Generar el contenido del PDF
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, 'Este mensaje ha sido enviado por Pelimarket', 0, 1);
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Para: ' . $nombre, 0, 1);
$pdf->Cell(0, 10, 'Telefono: ' . $telefono, 0, 1);
$pdf->Cell(0, 10, 'Peliculas:', 0, 1);


if ($resultado_carrito && $resultado_carrito->num_rows > 0) {
    while ($fila_carrito = mysqli_fetch_assoc($resultado_carrito)) {
        // Obtener los datos específicos del carrito
        $id_producto = $fila_carrito['id_producto'];
        // Otros campos del carrito


        // Consultar la información del producto a partir de su ID
        $sql_producto = "SELECT titulo FROM productos WHERE id = $id_producto";
        $resultado_producto = $con->query($sql_producto);
        if ($resultado_producto && $resultado_producto->num_rows > 0) {
            $fila_producto = mysqli_fetch_assoc($resultado_producto);
            $titulo_producto = $fila_producto['titulo'];


            // Agregar los datos del carrito al PDF
            $pdf->Cell(0, 10, "\t\t$titulo_producto", 0, 1);
            // Agregar otros campos del carrito al PDF
        }
    }
}


$fecha = date('l jS \of F Y h:i:s A');
$pdf->Cell(0, 10, 'Fecha: ' . $fecha, 0, 1);
$pdf->Cell(0, 10, 'Total: $' . $total_compra, 0, 1); // Usar $total_compra en lugar de $total


// Guardar el PDF en el servidor
$pdfPath = '../pdf/orden_' . $id_usuario . '.pdf';
$pdf->Output($pdfPath, 'F');


// Definir los encabezados del correo electrónico
$mail = new PHPMailer();
$mail->CharSet = 'utf-8';
$mail->Host = "smtp.gmail.com";
$mail->From = " 'erick556luna@gmail.com'";
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Username = " 'erick556luna@gmail.com'";
$mail->Password = "jhtpzlydoampldan";
$mail->Port = 587;
$mail->AddAddress($correo);
$mail->SMTPDebug = 0;
$mail->isHTML(true);
$mail->Subject = 'Gracias por la Compra!';
$mail->Body = '<b>Este es el recibo de tu compra:)</b>';
$mail->AltBody = 'Hemos enviado el recibo';


$inMailFileName = "recibo.pdf";
$filePath = "../pdf/orden_" . $id_usuario . ".pdf";
$mail->AddAttachment($filePath, $inMailFileName);


$mail->send();


// Actualizar el total en la tabla 'historial' para el usuario actual
$query = "INSERT INTO historial (id_usuario, total_compra) VALUES ('$id_usuario', '$total_compra')";
$sql_query = mysqli_query($con, $query);


if ($sql_query) {
    $query = "DELETE FROM carrito WHERE id_usuario = $id_usuario";
    $sql_query = mysqli_query($con, $query);


    if ($sql_query) {
        header("Location: ../confirmacion.php");
    } else {
        echo "Error al comprar.";
    }
} else {
    echo "Error al registrar la transacción: " . mysqli_error($con);
}


?>
