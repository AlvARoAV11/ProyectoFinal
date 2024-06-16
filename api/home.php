<?php
session_start();
require_once 'Entitys/User.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$usuario = new Usuario();
if (!$usuario->loadByEmail($_SESSION['usuario_email'])) {
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - BookMe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="Estilos/homeStyle.css">
</head>

<body>
    <div id="background-carousel">
        <img class="bg-image active" src="Assets/Casa.jpg" alt="Fondo 1">
        <img class="bg-image" src="Assets/Casa1.jpg" alt="Fondo 2">
        <img class="bg-image" src="Assets/hotel.jpg" alt="Fondo 3">
        <img class="bg-image" src="Assets/hotel2.jpg" alt="Fondo 3">
        <img class="bg-image" src="Assets/hotel1.jpg" alt="Fondo 3">
        <img class="bg-image" src="Assets/Rest.webp" alt="Fondo 3">
    </div>
    <?php include 'navbar.php'; ?>
    <br>
    <div class="container">
        <h1 class="mt-5 animate__animated animate__fadeInDown">Bienvenido, <?php echo htmlspecialchars($usuario->fullName); ?>!</h1>
        <br><br>
        <div class="row">
            <div class="col-md-4">
                <div class="card animate__animated animate__fadeInLeft">
                    <img src="Assets/reserva.jpg" class="card-img-top" alt="Realizar una Reserva">
                    <div class="card-body text-center">
                        <h5 class="card-title">Realizar una Reserva</h5>
                        <p class="card-text">Reserve su lugar en restaurantes, hoteles y más.</p>
                        <a href="reservar.php" class="btn btn-primary">Hacer Reserva</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card animate__animated animate__fadeInUp">
                    <img src="Assets/calendario.jpg" class="card-img-top" alt="Ver Reservas">
                    <div class="card-body text-center">
                        <h5 class="card-title">Ver Reservas</h5>
                        <p class="card-text">Vea y gestione sus reservas actuales.</p>
                        <a href="misReservas.php" class="btn btn-primary">Ver Reservas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card animate__animated animate__fadeInRight">
                    <img src="Assets/perfil.png" class="card-img-top" alt="Actualizar Perfil">
                    <div class="card-body text-center">
                        <h5 class="card-title">Actualizar Perfil</h5>
                        <p class="card-text">Actualice su información personal.</p>
                        <a href="perfil.php" class="btn btn-primary">Actualizar Perfil</a>
                    </div>
                </div>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>