<?php
require_once 'Entitys/Reserva.php';

header('Content-Type: application/json');

$establecimiento_id = $_GET['establecimiento_id'];
$reserva = new Reserva();
$disponibilidad = $reserva->getDisponibilidad($establecimiento_id);

echo json_encode($disponibilidad);
