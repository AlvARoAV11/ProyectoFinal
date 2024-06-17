<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Establecimientos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./../../Assets/css/IndexStyle.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand text-white" href="./../index.php">ReservaApp</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="#loginModal" data-toggle="modal">Iniciar Sesión</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#registerModal" data-toggle="modal">Registrarse</a>
                </li>
            </ul>
        </div>
    </nav>

    <div id="background-carousel">
        <img class="bg-image active" src="./../../assets/imagenes/Casa.jpg" alt="Fondo 1">
        <img class="bg-image" src="./../../assets/imagenes/Casa1.jpg" alt="Fondo 2">
        <img class="bg-image" src="./../../assets/imagenes/hotel.jpg" alt="Fondo 3">
        <img class="bg-image" src="./../../assets/imagenes/hotel2.jpg" alt="Fondo 3">
        <img class="bg-image" src="./../../assets/imagenes/hotel1.jpg" alt="Fondo 3">
        <img class="bg-image" src="./../../assets/imagenes/Rest.webp" alt="Fondo 3">
    </div>

    <header class="header text-center">
        <div class="container">
            <h1 class="display-4">Reserva tu lugar favorito</h1>
            <p class="lead">Encuentra y reserva en restaurantes, hoteles y mucho más.</p>
            <div>
                <?php
                if (isset($_GET['error'])) {
                    $error = $_GET['error'];
                    $errorMessage = '';
                    switch ($error) {
                        case 'empty_fields':
                            $errorMessage = 'Por favor, complete todos los campos.';
                            break;
                        case 'invalid_email':
                            $errorMessage = 'Por favor, ingrese un correo electrónico válido.';
                            break;
                        case 'login_failed':
                            $errorMessage = 'Correo electrónico o contraseña incorrectos.';
                            break;
                    }
                    if ($errorMessage) {
                        echo "<div class='alert alert-danger'>$errorMessage</div>";
                    }
                }
                ?>
            </div>
            <a href="#loginModal" class="btn btn-lg btn_comenzar" data-toggle="modal">Comienza Ahora</a>
        </div>
    </header>

    <!-- Inicio de Sesión -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" action="./../Process/dologin.php" method="post">
                        <div class="form-group">
                            <label for="loginEmail">Correo Electrónico</label>
                            <input type="email" class="form-control" id="loginEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="loginPassword">Contraseña</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Registro -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Registrarse</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="registerForm" action="Registro.php" method="post">
                        <div class="form-group">
                            <label for="registerFullName">Nombre Completo</label>
                            <input type="text" class="form-control" id="registerFullName" name="fullName" required>
                        </div>
                        <div class="form-group">
                            <label for="registerUsername">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="registerUsername" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="registerEmail">Correo Electrónico</label>
                            <input type="email" class="form-control" id="registerEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="registerPassword">Contraseña</label>
                            <input type="password" class="form-control" id="registerPassword" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="registerPhone">Número de Teléfono</label>
                            <input type="tel" class="form-control" id="registerPhone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="registerAddress">Dirección</label>
                            <input type="text" class="form-control" id="registerAddress" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="registerDOB">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="registerDOB" name="dob" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>