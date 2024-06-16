<?php
session_start();

// Verificar si el administrador ha iniciado sesión
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

require_once '../Entitys/Establecimiento.php';

// Obtener todos los establecimientos
$establecimientos = Establecimiento::obtenerEstablecimientos();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Establecimientos - BookMe</title>
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
        <h1 class="text-center mb-4">Gestionar Establecimientos</h1>
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
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Dirección</th>
                        <th>Ciudad</th>
                        <th>Provincia</th>
                        <th>Comunidad Autónoma</th>
                        <th>Contacto</th>
                        <th>Descripción</th>
                        <th>Capacidad</th>
                        <th>Máximo de Reservas por Día</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($establecimientos as $establecimiento) : ?>
                        <tr>
                            <td><?= htmlspecialchars($establecimiento->getId()); ?></td>
                            <td><?= htmlspecialchars($establecimiento->getNombre()); ?></td>
                            <td><?= htmlspecialchars($establecimiento->getTipo()); ?></td>
                            <td><?= htmlspecialchars($establecimiento->getDireccion()); ?></td>
                            <td><?= htmlspecialchars($establecimiento->getCiudad()); ?></td>
                            <td><?= htmlspecialchars($establecimiento->getProvincia()); ?></td>
                            <td><?= htmlspecialchars($establecimiento->getComunidadAutonoma()); ?></td>
                            <td><?= htmlspecialchars($establecimiento->getContacto()); ?></td>
                            <td><?= htmlspecialchars($establecimiento->getDescripcion()); ?></td>
                            <td>
                                <?php
                                $capacidad = $establecimiento->getCapacidad();
                                foreach ($capacidad as $tipo => $detalles) {
                                    echo htmlspecialchars($tipo) . ': ' . htmlspecialchars($detalles['capacidad']) . '<br>';
                                }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($establecimiento->getMaxReservasPorDia()); ?></td>
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalFotos<?= $establecimiento->getId(); ?>">Ver Fotos</button>

                                <div class="modal fade" id="modalFotos<?= $establecimiento->getId(); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Fotos de <?= htmlspecialchars($establecimiento->getNombre()); ?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="carouselFotos<?= $establecimiento->getId(); ?>" class="carousel slide" data-ride="carousel">
                                                    <div class="carousel-inner">
                                                        <?php
                                                        $imagenes = $establecimiento->getImagenes();
                                                        foreach ($imagenes as $index => $imagen) :
                                                        ?>
                                                            <div class="carousel-item <?= $index == 0 ? 'active' : ''; ?>">
                                                                <img src="data:image/jpeg;base64,<?= base64_encode($imagen['imagen']); ?>" class="d-block w-100" alt="Foto de <?= htmlspecialchars($establecimiento->getNombre()); ?>">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <a class="carousel-control-prev" href="#carouselFotos<?= $establecimiento->getId(); ?>" role="button" data-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Previous</span>
                                                    </a>
                                                    <a class="carousel-control-next" href="#carouselFotos<?= $establecimiento->getId(); ?>" role="button" data-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Next</span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="eliminar_establecimiento_admin.php" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este establecimiento?');" style="display:inline;">
                                    <input type="hidden" name="establecimiento_id" value="<?= htmlspecialchars($establecimiento->getId()); ?>">
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>