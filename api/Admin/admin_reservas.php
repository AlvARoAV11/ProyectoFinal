<?php
session_start();

// Verificar si el administrador ha iniciado sesión
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

require_once '../Entitys/Reserva.php';

// Obtener todas las reservas
$reservas = Reserva::obtenerTodasLasReservas();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Reservas - BookMe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Estilos/adminStyle.css">
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
        <h1 class="text-center mb-4">Gestionar Reservas</h1>
        <?php if (isset($_SESSION['mensaje'])) : ?>
            <div class="alert alert-success">
                <?= $_SESSION['mensaje'];
                unset($_SESSION['mensaje']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Reserva ID</th>
                        <th>Usuario ID</th>
                        <th>Establecimiento</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Número de Personas</th>
                        <th>Comentarios</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva) : ?>
                        <tr>
                            <td><?= htmlspecialchars($reserva->getId()); ?></td>
                            <td><?= htmlspecialchars($reserva->getUsuarioId()); ?></td>
                            <td><?= htmlspecialchars($reserva->getFechaInicio()); ?></td>
                            <td><?= htmlspecialchars($reserva->getFechaFin()); ?></td>
                            <td><?= htmlspecialchars($reserva->getNumPersonas()); ?></td>
                            <td><?= htmlspecialchars($reserva->getComentarios()); ?></td>
                            <td>
                                <form action="eliminar_reserva_admin.php" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta reserva?');">
                                    <input type="hidden" name="reserva_id" value="<?= htmlspecialchars($reserva->getId()); ?>">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-4">
            <a href="admin_home.php" class="btn btn-secondary">Volver al Panel de Administración</a>
        </div>
    </div>

    <footer class="footer text-center">
        <div class="container">
            <span>&copy; 2024 BookMe. Todos los derechos reservados.</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>