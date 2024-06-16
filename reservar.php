<?php
require_once 'Entitys/Establecimiento.php';

$establecimientos = Establecimiento::obtenerEstablecimientos();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Establecimientos - BookMe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="Estilos/reservarStyle.css">
</head>

<body>
    <div class="background"></div>

    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Nuestros Establecimientos</h1>
        <div class="card-container">
            <?php foreach ($establecimientos as $establecimiento) : ?>
                <div class="card animate__animated animate__fadeInUp">
                    <?php if (!empty($establecimiento->getImagenes())) : ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($establecimiento->getImagenes()[0]['imagen']) ?>" class="card-img-top" alt="Imagen de <?= $establecimiento->getNombre() ?>">
                    <?php else : ?>
                        <img src="path_to_default_image/default.jpg" class="card-img-top" alt="Imagen por defecto">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= $establecimiento->getNombre() ?></h5>
                        <p class="card-text"><?= $establecimiento->getDescripcion() ?></p>
                        <a href="detalle_establecimiento.php?id=<?= $establecimiento->getId() ?>" class="btn card-link">Ver detalles</a>
                    </div>
                </div>
                <br>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        var currentIndex = 0;
        var images = document.querySelectorAll('.bg-image');

        function changeBackground() {
            images[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % images.length;
            images[currentIndex].classList.add('active');
        }

        setInterval(changeBackground, 6000);
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>