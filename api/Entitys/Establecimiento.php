<?php
require_once 'Database.php';

class Establecimiento
{
    private $conn;

    public $id;
    public $nombre;
    public $tipo;
    public $direccion;
    public $ciudad;
    public $provincia;
    public $comunidadAutonoma;
    public $descripcion;
    public $estrellas;
    public $capacidad;
    public $contacto;
    public $maxReservasPorDia;
    public $imagenes = [];

    public function __construct($id = null)
    {
        $database = new Database();
        $this->conn = $database->getConnection();

        if ($id) {
            $this->loadById($id);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    // Cargar establecimiento por id
    private function loadById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM establecimientos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->tipo = $row['tipo'];
            $this->direccion = $row['direccion'];
            $this->ciudad = $row['ciudad'];
            $this->provincia = $row['provincia'];
            $this->comunidadAutonoma = $row['comunidad_autonoma'];
            $this->contacto = $row['contacto'];
            $this->descripcion = $row['descripcion'];
            $this->estrellas = $row['estrellas'];
            $this->maxReservasPorDia = $row['max_reservas_por_dia'];
            $this->capacidad = json_decode($row['capacidad'], true);

            // Cargar imágenes
            $this->loadImages();
        }
    }

    // Cargar imágenes del establecimiento
    private function loadImages()
    {
        $query = "SELECT * FROM imagenes_establecimientos WHERE establecimiento_id = :id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $this->id, PDO::PARAM_INT);
        $statement->execute();

        $this->imagenes = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los establecimientos
    public static function obtenerEstablecimientos()
    {
        $establecimientos = [];

        try {
            $database = new Database();
            $conexion = $database->getConnection();

            $query = "SELECT * FROM establecimientos";
            $statement = $conexion->prepare($query);
            $statement->execute();

            $resultados = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $fila) {
                $establecimiento = new Establecimiento();
                $establecimiento->id = $fila['id'];
                $establecimiento->nombre = $fila['nombre'];
                $establecimiento->tipo = $fila['tipo'];
                $establecimiento->direccion = $fila['direccion'];
                $establecimiento->ciudad = $fila['ciudad'];
                $establecimiento->provincia = $fila['provincia'];
                $establecimiento->comunidadAutonoma = $fila['comunidad_autonoma'];
                $establecimiento->descripcion = $fila['descripcion'];
                $establecimiento->estrellas = $fila['estrellas'];
                $establecimiento->capacidad = json_decode($fila['capacidad'], true);
                $establecimiento->contacto = $fila['contacto'];
                $establecimiento->maxReservasPorDia = $fila['max_reservas_por_dia'];

                // Añadir imágenes
                $establecimiento->loadImages();

                $establecimientos[] = $establecimiento;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return $establecimientos;
    }

    public function crearEstablecimiento($nombre, $tipo, $direccion, $ciudad, $provincia, $comunidad_autonoma, $contacto, $descripcion, $capacidad, $max_reservas_por_dia, $estrellas = null)
    {
        try {
            $database = new Database();
            $conexion = $database->getConnection();

            $query = "INSERT INTO establecimientos (nombre, tipo, direccion, ciudad, provincia, comunidad_autonoma, contacto, descripcion, capacidad, max_reservas_por_dia, estrellas) 
                  VALUES (:nombre, :tipo, :direccion, :ciudad, :provincia, :comunidad_autonoma, :contacto, :descripcion, :capacidad, :max_reservas_por_dia, :estrellas)";

            $statement = $conexion->prepare($query);
            $statement->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $statement->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $statement->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $statement->bindParam(':ciudad', $ciudad, PDO::PARAM_STR);
            $statement->bindParam(':provincia', $provincia, PDO::PARAM_STR);
            $statement->bindParam(':comunidad_autonoma', $comunidad_autonoma, PDO::PARAM_STR);
            $statement->bindParam(':contacto', $contacto, PDO::PARAM_STR);
            $statement->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $statement->bindParam(':capacidad', $capacidad, PDO::PARAM_STR);
            $statement->bindParam(':max_reservas_por_dia', $max_reservas_por_dia, PDO::PARAM_INT);
            if ($estrellas !== null) {
                $statement->bindParam(':estrellas', $estrellas, PDO::PARAM_INT);
            } else {
                $statement->bindValue(':estrellas', null, PDO::PARAM_NULL);
            }

            $statement->execute();

            // Retornar el ID del establecimiento recién creado
            return $conexion->lastInsertId();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


    public function eliminarEstablecimiento($id)
    {
        try {
            $database = new Database();
            $conexion = $database->getConnection();

            // Eliminar imágenes asociadas al establecimiento
            $query = "DELETE FROM imagenes_establecimientos WHERE establecimiento_id = :id";
            $statement = $conexion->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            // Eliminar el establecimiento
            $query = "DELETE FROM establecimientos WHERE id = :id";
            $statement = $conexion->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function guardarImagen($establecimiento_id, $imagen)
    {
        try {
            $database = new Database();
            $conexion = $database->getConnection();

            $query = "INSERT INTO imagenes_establecimientos (establecimiento_id, imagen) VALUES (:establecimiento_id, :imagen)";
            $statement = $conexion->prepare($query);
            $statement->bindParam(':establecimiento_id', $establecimiento_id, PDO::PARAM_INT);
            $statement->bindParam(':imagen', $imagen, PDO::PARAM_LOB);
            $statement->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function getNombre()
    {
        return $this->nombre;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getCiudad()
    {
        return $this->ciudad;
    }

    public function getProvincia()
    {
        return $this->provincia;
    }

    public function getComunidadAutonoma()
    {
        return $this->comunidadAutonoma;
    }

    public function getContacto()
    {
        return $this->contacto;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getEstrellas()
    {
        return $this->estrellas;
    }

    public function getCapacidad()
    {
        return $this->capacidad;
    }

    public function getMaxReservasPorDia()
    {
        return $this->maxReservasPorDia;
    }

    public function getImagenes()
    {
        return $this->imagenes;
    }
}
