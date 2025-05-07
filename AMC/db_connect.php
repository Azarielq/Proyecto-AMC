<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "control_clientes";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar charset
$conn->set_charset("utf8mb4");
?>