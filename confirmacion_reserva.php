<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <h1 class="text-center">Confirmación de Reserva</h1>
                <p class="text-center"><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
                <div class="text-center">
                    <a href="home.php" class="btn btn-primary">Volver al Inicio</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>