<?php
require_once 'Database.php';

class Reserva
{
    private $id;
    private $usuario_id;
    private $establecimiento_id;
    private $fecha_inicio;
    private $fecha_fin;
    private $comentarios;
    private $num_personas;
    private $conn;

    public function __construct($id = null)
    {
        $database = new Database();
        $this->conn = $database->getConnection();

        if ($id) {
            $this->loadById($id);
        }
    }

    public function crearReserva($usuario_id, $establecimiento_id, $fecha_inicio, $fecha_fin, $num_personas, $comentarios)
    {
        // Verificar disponibilidad
        if (!$this->verificarDisponibilidad($establecimiento_id, $fecha_inicio, $fecha_fin)) {
            error_log("Fechas no disponibles: $fecha_inicio a $fecha_fin");
            return false;
        }

        // Verificar capacidad del establecimiento
        if (!$this->verificarCapacidad($establecimiento_id, $num_personas)) {
            error_log("Capacidad superada para el establecimiento $establecimiento_id con $num_personas personas");
            return false;
        }

        // Verificar que la fecha de inicio no sea anterior a hoy
        if (!$this->verificarFechaInicio($fecha_inicio)) {
            error_log("Fecha de inicio anterior a hoy: $fecha_inicio");
            return false;
        }

        try {
            $query = "INSERT INTO reservas (usuario_id, establecimiento_id, fecha_inicio, fecha_fin, num_personas, comentarios) 
                  VALUES (:usuario_id, :establecimiento_id, :fecha_inicio, :fecha_fin, :num_personas, :comentarios)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(':establecimiento_id', $establecimiento_id, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $stmt->bindParam(':num_personas', $num_personas, PDO::PARAM_INT);
            $stmt->bindParam(':comentarios', $comentarios, PDO::PARAM_STR);
            $stmt->execute();

            // Devolver el id de la reserva creada
            return $this->conn->lastInsertId(); 
        } catch (PDOException $e) {
            error_log("Error en la inserción de reserva: " . $e->getMessage());
            return false;
        }
    }

    private function verificarCapacidad($establecimiento_id, $num_personas)
    {
        $stmt = $this->conn->prepare("SELECT capacidad FROM establecimientos WHERE id = :establecimiento_id");
        $stmt->bindParam(':establecimiento_id', $establecimiento_id, PDO::PARAM_INT);
        $stmt->execute();
        $capacidad = json_decode($stmt->fetch(PDO::FETCH_ASSOC)['capacidad'], true);

        $capacidad_minima = PHP_INT_MAX;
        $capacidad_maxima = 0;

        foreach ($capacidad as $tipo => $detalles) {
            if (isset($detalles['capacidad'])) {
                $capacidad_minima = min($capacidad_minima, $detalles['capacidad']);
                $capacidad_maxima = max($capacidad_maxima, $detalles['capacidad']);
            }
        }

        return $num_personas >= $capacidad_minima && $num_personas <= $capacidad_maxima;
    }

    private function verificarFechaInicio($fecha_inicio)
    {
        $hoy = date('Y-m-d');
        return $fecha_inicio >= $hoy;
    }

    public function verificarDisponibilidad($establecimiento_id, $fecha_inicio, $fecha_fin)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM reservas WHERE establecimiento_id = :establecimiento_id AND (fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin OR fecha_fin BETWEEN :fecha_inicio AND :fecha_fin OR :fecha_inicio BETWEEN fecha_inicio AND fecha_fin OR :fecha_fin BETWEEN fecha_inicio AND fecha_fin)");
        $stmt->bindParam(':establecimiento_id', $establecimiento_id, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmtMax = $this->conn->prepare("SELECT max_reservas_por_dia FROM establecimientos WHERE id = :establecimiento_id");
        $stmtMax->bindParam(':establecimiento_id', $establecimiento_id, PDO::PARAM_INT);
        $stmtMax->execute();
        $maxReservas = $stmtMax->fetch(PDO::FETCH_ASSOC)['max_reservas_por_dia'];

        return $result['count'] < $maxReservas;
    }

    public function getDisponibilidad($establecimiento_id)
    {
        // Obtener el máximo de reservas por día del establecimiento
        $stmtMax = $this->conn->prepare("SELECT max_reservas_por_dia FROM establecimientos WHERE id = :establecimiento_id");
        $stmtMax->bindParam(':establecimiento_id', $establecimiento_id, PDO::PARAM_INT);
        $stmtMax->execute();
        $maxReservas = $stmtMax->fetch(PDO::FETCH_ASSOC)['max_reservas_por_dia'];

        $stmt = $this->conn->prepare("SELECT fecha_inicio, fecha_fin FROM reservas WHERE establecimiento_id = :establecimiento_id");
        $stmt->bindParam(':establecimiento_id', $establecimiento_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $disponibilidad = [];
        foreach ($result as $row) {
            $start = new DateTime($row['fecha_inicio']);
            $end = new DateTime($row['fecha_fin']);
            $end->modify('+1 day');
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start, $interval, $end);

            foreach ($period as $dt) {
                $date = $dt->format('Y-m-d');
                if (!isset($disponibilidad[$date])) {
                    $disponibilidad[$date] = 0;
                }
                $disponibilidad[$date]++;
            }
        }

        $events = [];
        foreach ($disponibilidad as $date => $count) {
            $color = ($count >= $maxReservas) ? 'red' : 'green';
            $events[] = [
                'title' => $count . ' reservas',
                'start' => $date,
                'color' => $color
            ];
        }

        return $events;
    }


    private function loadById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM reservas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->usuario_id = $row['usuario_id'];
            $this->establecimiento_id = $row['establecimiento_id'];
            $this->fecha_inicio = $row['fecha_inicio'];
            $this->fecha_fin = $row['fecha_fin'];
            $this->comentarios = $row['comentarios'];
            $this->num_personas = $row['num_personas'];
        }
    }

    public function obtenerReservasPorUsuario($usuario_id)
    {
        try {
            $query = "SELECT reservas.*, establecimientos.nombre AS nombre_establecimiento 
                  FROM reservas 
                  JOIN establecimientos ON reservas.establecimiento_id = establecimientos.id 
                  WHERE reservas.usuario_id = :usuario_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public static function obtenerTodasLasReservas()
    {
        $reservas = [];

        try {
            $database = new Database();
            $conexion = $database->getConnection();

            $query = "SELECT reservas.*, establecimientos.nombre AS nombre_establecimiento 
                  FROM reservas 
                  JOIN establecimientos ON reservas.establecimiento_id = establecimientos.id";
            $statement = $conexion->prepare($query);
            $statement->execute();

            $resultados = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $fila) {
                $reserva = new Reserva();
                $reserva->id = $fila['id'];
                $reserva->usuario_id = $fila['usuario_id'];
                $reserva->establecimiento_id = $fila['establecimiento_id'];
                $reserva->fecha_inicio = $fila['fecha_inicio'];
                $reserva->fecha_fin = $fila['fecha_fin'];
                $reserva->num_personas = $fila['num_personas'];
                $reserva->comentarios = $fila['comentarios'];

                $reservas[] = $reserva;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return $reservas;
    }
    public function eliminarReserva($reserva_id)
    {
        try {
            $query = "DELETE FROM reservas WHERE id = :reserva_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':reserva_id', $reserva_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function actualizarQR($reserva_id, $qr_file)
    {
        try {
            $query = "UPDATE reservas SET qr_code_path = :qr_file WHERE id = :reserva_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':qr_file', $qr_file, PDO::PARAM_STR);
            $stmt->bindParam(':reserva_id', $reserva_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function getEstablecimientoId()
    {
        return $this->establecimiento_id;
    }

    public function getFechaInicio()
    {
        return $this->fecha_inicio;
    }

    public function getFechaFin()
    {
        return $this->fecha_fin;
    }

    public function getComentarios()
    {
        return $this->comentarios;
    }

    public function getNumPersonas()
    {
        return $this->num_personas;
    }
}
