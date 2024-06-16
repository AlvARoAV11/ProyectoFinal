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
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Establecimiento - BookMe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Estilos/adminStyle.css">
    <script>
        function toggleEstrellas() {
            var tipo = document.getElementById('tipo').value;
            var estrellasGroup = document.getElementById('estrellas-group');
            if (tipo === 'hotel') {
                estrellasGroup.style.display = 'block';
            } else {
                estrellasGroup.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleEstrellas();
        });
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">BookMe Admin</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Añadir Establecimiento</h1>
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <form action="prueba.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <select class="form-control" id="tipo" name="tipo" required onchange="toggleEstrellas()">
                    <option value="hotel">Hotel</option>
                    <option value="casa_rural">Casa Rural</option>
                    <option value="albergue">Albergue</option>
                </select>
            </div>
            <div class="form-group" id="estrellas-group" style="display: none;">
                <label for="estrellas">Estrellas (solo para hoteles):</label>
                <input type="number" class="form-control" id="estrellas" name="estrellas" min="1" max="5">
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="ciudad">Ciudad:</label>
                <input type="text" class="form-control" id="ciudad" name="ciudad" required>
            </div>
            <div class="form-group">
                <label for="provincia">Provincia:</label>
                <input type="text" class="form-control" id="provincia" name="provincia" required>
            </div>
            <div class="form-group">
                <label for="comunidad_autonoma">Comunidad Autónoma:</label>
                <input type="text" class="form-control" id="comunidad_autonoma" name="comunidad_autonoma" required>
            </div>
            <div class="form-group">
                <label for="contacto">Contacto:</label>
                <input type="text" class="form-control" id="contacto" name="contacto" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="capacidad_minima">Capacidad Mínima:</label>
                <input type="number" class="form-control" id="capacidad_minima" name="capacidad_minima" required>
            </div>
            <div class="form-group">
                <label for="capacidad_maxima">Capacidad Máxima:</label>
                <input type="number" class="form-control" id="capacidad_maxima" name="capacidad_maxima" required>
            </div>
            <div class="form-group">
                <label for="max_reservas_por_dia">Máximo de Reservas por Día:</label>
                <input type="number" class="form-control" id="max_reservas_por_dia" name="max_reservas_por_dia" required>
            </div>
            <div class="form-group">
                <label for="imagenes">Imágenes:</label>
                <input type="file" class="form-control-file" id="imagenes" name="imagenes[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Añadir Establecimiento</button>
        </form>
        <div class="text-center mt-4">
            <a href="admin_home.php" class="btn btn-secondary">Volver al Panel de Administración</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>