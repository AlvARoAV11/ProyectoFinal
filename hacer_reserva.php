<?php
require_once 'Entitys/Reserva.php';
require_once 'lib/phpqrcode/qrlib.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

// Comprobar datos mandados
if (isset($_POST['establecimiento_id'], $_POST['fecha_inicio'], $_POST['fecha_fin'], $_POST['num_personas'], $_POST['comentarios'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $establecimiento_id = $_POST['establecimiento_id'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $num_personas = $_POST['num_personas'];
    $comentarios = $_POST['comentarios'];

    // Crear reserva
    $reserva = new Reserva();
    $reserva_id = $reserva->crearReserva($usuario_id, $establecimiento_id, $fecha_inicio, $fecha_fin, $num_personas, $comentarios);

    if ($reserva_id) {
        // Generar el contenido del qr
        $qr_content = "Reserva ID: $reserva_id\nUsuario ID: $usuario_id\nEstablecimiento ID: $establecimiento_id\nFecha Inicio: $fecha_inicio\nFecha Fin: $fecha_fin\nNúmero de Personas: $num_personas\nComentarios: $comentarios";

        $qr_dir = 'qr_codes/';
        if (!file_exists($qr_dir)) {
            mkdir($qr_dir, 0777, true);
        }
        $qr_file = $qr_dir . "reserva_$reserva_id.png";

        // Generar el código qr
        QRcode::png($qr_content, $qr_file);

        // Guardar qr en la bd
        $reserva->actualizarQR($reserva_id, $qr_file);
        
        $_SESSION['mensaje'] = "Reserva creada con éxito.";
        header("Location: misReservas.php");
        exit;
    } else {
        $_SESSION['mensaje'] = "Error al crear la reserva.";
        var_dump($reserva_id);
        header("Location: misReservas.php");
        exit;
    }
}

header("Location: misReservas.php");
exit;
