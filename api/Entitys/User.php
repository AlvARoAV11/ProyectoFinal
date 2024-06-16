<?php
require_once 'Database.php';

class Usuario
{
    private $conn;

    public $id;
    public $fullName;
    public $username;
    public $email;
    public $password;
    public $phone;
    public $address;
    public $dob;

    public function __construct($id = null)
    {
        $database = new Database();
        $this->conn = $database->getConnection();

        if ($id) {
            $this->loadById($id);
        }
    }

    public function registrar()
    {
        // Validar campos
        if (!$this->validarCampos()) {
            return false;
        }

        // Validar contraseña
        if (!$this->validarContrasena()) {
            return false;
        }

        // Validar número de teléfono
        if (!$this->validarTelefono()) {
            return false;
        }

        // Validar existencia de nombre de usuario y correo electrónico
        if ($this->existeUsuario()) {
            echo "El nombre de usuario ya está en uso.";
            return false;
        }

        if ($this->existeCorreo()) {
            echo "El correo electrónico ya está en uso.";
            return false;
        }

        try {
            // Hashear la contraseña
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

            // Preparar la sentencia SQL
            $stmt = $this->conn->prepare("INSERT INTO usuarios (fullName, username, email, password, phone, address, dob) VALUES (:fullName, :username, :email, :password, :phone, :address, :dob)");
            $stmt->bindParam(':fullName', $this->fullName);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':phone', $this->phone);
            $stmt->bindParam(':address', $this->address);
            $stmt->bindParam(':dob', $this->dob);

            // Ejecutar la sentencia
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error al registrar usuario: " . $e->getMessage();
            return false;
        }
    }

    public function iniciarSesion($email, $password)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Verificar si se encontraron resultados
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Verificar la contraseña
                if (password_verify($password, $row['password'])) {
                    $this->id = $row['id'];
                    $this->fullName = $row['fullName'];
                    $this->username = $row['username'];
                    $this->email = $row['email'];
                    $this->password = $row['password'];
                    $this->phone = $row['phone'];
                    $this->address = $row['address'];
                    $this->dob = $row['dob'];

                    return true;
                } else {
                    echo "Contraseña incorrecta.";
                    return false;
                }
            } else {
                echo "Correo electrónico no encontrado.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al iniciar sesión: " . $e->getMessage();
            return false;
        }
    }

    private function validarCampos()
    {
        if (empty($this->fullName) || empty($this->username) || empty($this->email) || empty($this->password) || empty($this->phone) || empty($this->address)) {
            echo "Todos los campos son obligatorios.";
            return false;
        }

        $regex = "/^[a-zA-Z0-9\s]+$/";

        if (!preg_match($regex, $this->fullName) || !preg_match($regex, $this->username) || !filter_var($this->email, FILTER_VALIDATE_EMAIL) || !preg_match($regex, $this->address)) {
            echo "Los campos no pueden contener símbolos ni caracteres especiales.";
            return false;
        }

        return true;
    }

    private function validarContrasena()
    {
        // Validar longitud de la contraseña
        if (strlen($this->password) < 8) {
            echo "La contraseña debe tener al menos 8 caracteres.";
            return false;
        }

        // Validar que la contraseña contenga al menos un número y una letra
        if (!preg_match("/[0-9]/", $this->password) || !preg_match("/[a-zA-Z]/", $this->password)) {
            echo "La contraseña debe contener al menos un número y una letra.";
            return false;
        }

        return true;
    }

    private function validarTelefono()
    {
        // Validar que el teléfono contenga solo numeros
        if (!preg_match("/^[0-9]+$/", $this->phone)) {
            echo "El número de teléfono debe contener solo números.";
            return false;
        }

        return true;
    }

    private function existeUsuario()
    {
        try {
            // Preparar la consulta SQL
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE username = :username");
            $stmt->bindParam(':username', $this->username);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error al verificar existencia de usuario: " . $e->getMessage();
            return false;
        }
    }

    private function existeCorreo()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error al verificar existencia de correo electrónico: " . $e->getMessage();
            return false;
        }
    }

    private function loadById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->id = $row['id'];
                $this->fullName = $row['fullName'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                $this->phone = $row['phone'];
                $this->address = $row['address'];
                $this->dob = $row['dob'];
            }
        } catch (PDOException $e) {
            echo "Error al cargar usuario: " . $e->getMessage();
        }
    }

    public function loadByEmail($email)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->id = $row['id'];
                $this->fullName = $row['fullName'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                $this->phone = $row['phone'];
                $this->address = $row['address'];
                $this->dob = $row['dob'];

                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al cargar usuario: " . $e->getMessage();
            return false;
        }
    }
}
