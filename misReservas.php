<?php
require_once 'Entitys/Reserva.php';
require_once 'Entitys/User.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$reserva = new Reserva();
$reservas = $reserva->obtenerReservasPorUsuario($usuario_id);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - BookMe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="Estilos/misReservasStyle.css">
</head>

<body>
    <div class="background"></div>

    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Mis Reservas</h1>
        <?php if (isset($_SESSION['mensaje'])) : ?>
            <div class="alert alert-success">
                <?= $_SESSION['mensaje'];
                unset($_SESSION['mensaje']); ?>
            </div>
        <?php endif; ?>
        <?php if (empty($reservas)) : ?>
            <p class="text-center">No tienes reservas.</p>
        <?php else : ?>
            <div class="card-columns">
                <?php foreach ($reservas as $reserva) : ?>
                    <div class="card animate__animated animate__fadeInUp">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($reserva['nombre_establecimiento']); ?></h5>
                            <p class="card-text"><strong>Fecha de Inicio:</strong> <?= htmlspecialchars($reserva['fecha_inicio']); ?></p>
                            <p class="card-text"><strong>Fecha de Fin:</strong> <?= htmlspecialchars($reserva['fecha_fin']); ?></p>
                            <p class="card-text"><strong>Número de Personas:</strong> <?= htmlspecialchars($reserva['num_personas']); ?></p>
                            <p class="card-text"><strong>Comentarios:</strong> <?= htmlspecialchars($reserva['comentarios']); ?></p>
                            <?php if (!empty($reserva['qr_code_path'])) : ?>
                                <img src="<?= htmlspecialchars($reserva['qr_code_path']); ?>" alt="QR Code" class="img-fluid">
                            <?php endif; ?>
                            <form action="Process/eliminar_reserva.php" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta reserva?');">
                                <input type="hidden" name="reserva_id" value="<?= htmlspecialchars($reserva['id']); ?>">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>