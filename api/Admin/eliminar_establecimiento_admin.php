<?php
session_start();

// Verificar si el administrador ha iniciado sesión
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

require_once '../Entitys/Establecimiento.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $establecimiento_id = $_POST['establecimiento_id'];

    if (isset($establecimiento_id) && is_numeric($establecimiento_id)) {
        $establecimiento = new Establecimiento();

        if ($establecimiento->eliminarEstablecimiento($establecimiento_id)) {
            $_SESSION['mensaje'] = "Establecimiento eliminado con éxito.";
        } else {
            $_SESSION['error'] = "Error al eliminar el establecimiento. Inténtalo de nuevo.";
        }
    } else {
        $_SESSION['error'] = "ID de establecimiento no válido.";
    }

    header("Location: admin_establecimientos.php");
    exit;
}

header("Location: admin_establecimientos.php");
exit;
