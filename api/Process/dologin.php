<?php
require_once './../../Entitys/User.php';
session_start();

// Verificar que se haya enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validaciones datos
    if (empty($email) || empty($password)) {
        header('Location: ./../../index.php?error=empty_fields');
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ./../../index.php?error=invalid_email');
        exit();
    } else {

        if ($email == 'admin@gmail.com' && $password == 'admin') {
            $_SESSION['admin_logged_in'] = true;
            header("Location: ./../../Admin/admin_home.php");
            exit;
        }

        // Crear una instancia usuario
        $usuario = new Usuario();

        // iniciar sesion
        if ($usuario->iniciarSesion($email, $password)) {
            $_SESSION['usuario_id'] = $usuario->id;
            $_SESSION['usuario_username'] = $usuario->username;
            $_SESSION['usuario_email'] = $usuario->email;
            
            header('Location: ./../../home.php');
            exit();
        } else {
            header('Location: ./../../index.php?error=login_failed');
            exit();
        }
    }
}
