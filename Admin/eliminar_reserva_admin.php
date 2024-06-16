<?php
require_once '../Entitys/Reserva.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['reserva_id'])) {
    $reserva_id = $_POST['reserva_id'];

    // Crear una instancia de la clase Reserva
    $reserva = new Reserva();

    // eliminar la reserva
    if ($reserva->eliminarReserva($reserva_id)) {
        $_SESSION['mensaje'] = "Reserva eliminada con éxito.";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la reserva.";
    }
}

header("Location: ../Admin/admin_reservas.php");
exit;
