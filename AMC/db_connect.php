<?php
// db_connect.php

$servername = "localhost";
$username = "root";    // Usuario predeterminado XAMPP
$password = "";        // Contraseña predeterminada XAMPP (vacía)
$dbname = "control_clientes";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
