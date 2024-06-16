<?php
session_start();

// Verificar si el administrador ha iniciado sesión
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

require_once '../Entitys/Establecimiento.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $provincia = $_POST['provincia'];
    $comunidad_autonoma = $_POST['comunidad_autonoma'];
    $contacto = $_POST['contacto'];
    $descripcion = $_POST['descripcion'];
    $capacidad_minima = $_POST['capacidad_minima'];
    $capacidad_maxima = $_POST['capacidad_maxima'];
    $max_reservas_por_dia = $_POST['max_reservas_por_dia'];
    $estrellas = isset($_POST['estrellas']) ? $_POST['estrellas'] : null;

    // Crear el JSON de capacidad
    $capacidad = json_encode([
        "capacidad_maxima" => ["capacidad" => $capacidad_maxima],
        "capacidad_minima" => ["capacidad" => $capacidad_minima]
    ]);

    $establecimiento = new Establecimiento();
    $establecimiento_id = $establecimiento->crearEstablecimiento($nombre, $tipo, $direccion, $ciudad, $provincia, $comunidad_autonoma, $contacto, $descripcion, $capacidad, $max_reservas_por_dia, $estrellas);

    if ($establecimiento_id) {
        // Manejar la subida de imágenes
        if (isset($_FILES['imagenes']) && count($_FILES['imagenes']['tmp_name']) > 0) {
            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['imagenes']['error'][$key] === UPLOAD_ERR_OK) {
                    $imagen = file_get_contents($tmp_name);
                    $establecimiento->guardarImagen($establecimiento_id, $imagen);
                }
            }
        }

        // Redirigir a la página de gestión de establecimientos con un mensaje de éxito
        $_SESSION['mensaje'] = "Establecimiento añadido con éxito.";
        header("Location: admin_establecimientos.php");
        exit;
    } else {
        $_SESSION['error'] = "Error al crear el establecimiento.";
        var_dump($establecimiento_id);
    }
}
