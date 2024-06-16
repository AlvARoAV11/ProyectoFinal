<?php
require_once 'Entitys/User.php';

// Verificar que se haya enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $fullName = $_POST['fullName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];

    // Crear una instancia de la clase usuario
    $usuario = new Usuario();

    // dato del usuario
    $usuario->fullName = $fullName;
    $usuario->username = $username;
    $usuario->email = $email;
    $usuario->password = $password;
    $usuario->phone = $phone;
    $usuario->address = $address;
    $usuario->dob = $dob;

    if ($usuario->registrar()) {
        header("Location: Index.php");
    } else {
        
    }
}
