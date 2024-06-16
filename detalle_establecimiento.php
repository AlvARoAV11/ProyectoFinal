<?php
require_once 'Entitys/Establecimiento.php';
require_once 'Entitys/Reserva.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $establecimiento = new Establecimiento($_GET['id']);
} else {
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Establecimiento</title>
    <title>Home - BookMe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="Estilos/detallesStyle.css">
</head>

<body>
    <div class="background"></div>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <h1 class="text-center animated">Detalles del Establecimiento</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2 animated">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $establecimiento->getNombre(); ?></h5>
                        <p class="card-text">Descripción: <?php echo $establecimiento->getDescripcion(); ?></p>
                        <p class="card-text">Dirección: <?php echo $establecimiento->getDireccion(); ?></p>
                        <p class="card-text">Comunidad Autónoma: <?php echo $establecimiento->getComunidadAutonoma(); ?></p>
                        <p class="card-text">Provincia: <?php echo $establecimiento->getProvincia(); ?></p>
                        <p class="card-text">Contacto: <?php echo $establecimiento->getContacto(); ?></p>
                        <?php if ($establecimiento->getTipo() == 'hotel') : ?>
                            <p class="card-text">Estrellas: <?php echo $establecimiento->getEstrellas(); ?></p>
                        <?php endif; ?>
                        <p class="card-text">Capacidad:</p>
                        <ul>
                            <?php foreach ($establecimiento->getCapacidad() as $key => $value) : ?>
                                <li><?php echo ucfirst($key) . ': ' . $value['capacidad']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#imagenesModal">
                            Ver Imágenes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-8 offset-md-2 animated">
                <h3 class="text-center">Realizar una Reserva</h3>
                <p class="text-center">Arrastra en el calendario para seleccionar un rango de fechas</p>
                <form action="hacer_reserva.php" method="POST" id="reservation-form">
                    <input type="hidden" name="establecimiento_id" value="<?php echo $establecimiento->getId(); ?>">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio:</label>
                        <input type="text" id="fecha_inicio" name="fecha_inicio" class="form-control" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">Fecha de Fin:</label>
                        <input type="text" id="fecha_fin" name="fecha_fin" class="form-control" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="num_personas">Número de Personas:</label>
                        <input type="number" id="num_personas" name="num_personas" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="comentarios">Comentarios:</label>
                        <textarea id="comentarios" name="comentarios" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Reservar</button>
                </form>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imagenesModal" tabindex="-1" role="dialog" aria-labelledby="imagenesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagenesModalLabel">Imágenes del Establecimiento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($establecimiento->getImagenes() as $index => $imagen) : ?>
                                <div class="carousel-item <?php if ($index === 0) echo 'active'; ?>">
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($imagen['imagen']); ?>" class="d-block w-100" alt="Imagen del establecimiento">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Siguiente</span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    $('#fecha_inicio').val(moment(start).format('YYYY-MM-DD'));
                    $('#fecha_fin').val(moment(end).subtract(1, 'days').format('YYYY-MM-DD'));
                },
                events: 'obtener_disponibilidad.php?establecimiento_id=<?php echo $establecimiento->getId(); ?>',
                eventColor: '#378006'
            });
        });
    </script>

</body>

</html>