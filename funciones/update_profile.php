<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/pages-login.php");
    exit();
}

require '../db_connect.php';

// Procesar actualización de datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_account'])) {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $email = trim($_POST['email']);
    $usuario_id = $_SESSION['usuario_id'];

    // Validar datos
    if (empty($nombre_usuario) || empty($email)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El correo electrónico no es válido.";
    } else {
        // Verificar si el nombre de usuario o email ya existen (excluyendo al usuario actual)
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE (nombre_usuario = ? OR email = ?) AND id != ?");
        $stmt->bind_param("ssi", $nombre_usuario, $email, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $_SESSION['error'] = "El nombre de usuario o correo ya está en uso.";
        } else {
            // Actualizar datos
            $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nombre_usuario, $email, $usuario_id);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Datos actualizados correctamente.";
            } else {
                $_SESSION['error'] = "Error al actualizar los datos.";
            }
        }
        $stmt->close();
    }
}

$conn->close();

// Redirigir de vuelta a index.php
header("Location: ../index.php");
exit();
?>