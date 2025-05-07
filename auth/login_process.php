<?php
// Iniciar la sesión
session_start();

// Incluir la conexión a la base de datos
require '../db_connect.php';

// Función para depuración (solo para desarrollo)
function debug_to_file($data, $clear = false) {
    $mode = $clear ? 'w' : 'a';
    $file = fopen('debug_login.txt', $mode);
    if (is_array($data) || is_object($data)) {
        fwrite($file, print_r($data, true) . "\n");
    } else {
        fwrite($file, $data . "\n");
    }
    fclose($file);
}

// Limpiar archivo de depuración
debug_to_file('--- Nuevo intento de login ---', true);
debug_to_file('Tiempo: ' . date('Y-m-d H:i:s'));

// Comprobar si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Obtener los datos del formulario
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    debug_to_file("Email ingresado: $email");
    debug_to_file("Password ingresado: [OCULTO POR SEGURIDAD]");
    
    try {
        // Consulta más simple para verificar si el usuario existe
        $check_query = "SELECT * FROM usuarios WHERE email = '$email'";
        debug_to_file("Consulta de verificación: $check_query");
        
        $result = $conn->query($check_query);
        
        if ($result && $result->num_rows > 0) {
            debug_to_file("Usuario encontrado en la base de datos");
            
            // Obtener los datos del usuario
            $usuario = $result->fetch_assoc();
            
            // Depurar estructura de la tabla (nombres de columnas)
            debug_to_file("Columnas en la tabla usuarios:");
            debug_to_file(array_keys($usuario));
            
            // Verificar si existe el campo de contraseña
            if (isset($usuario['password'])) {
                debug_to_file("Campo de contraseña encontrado: 'password'");
                $stored_password = $usuario['password'];
            } elseif (isset($usuario['contraseña'])) {
                debug_to_file("Campo de contraseña encontrado: 'contraseña'");
                $stored_password = $usuario['contraseña'];
            } else {
                debug_to_file("ERROR: No se encontró campo de contraseña!");
                $_SESSION['error_login'] = 'Error en la configuración del sistema. Contacte al administrador.';
                header("Location: pages-login.php");
                exit;
            }
            
            // Intentar verificación
            debug_to_file("Intentando verificar contraseña...");
            $password_verified = password_verify($password, $stored_password);
            debug_to_file("Resultado de verificación: " . ($password_verified ? "ÉXITO" : "FALLIDO"));
            
            if ($password_verified) {
                // Si la contraseña es correcta, iniciar sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre_usuario'] = $usuario['nombre'] ?? $usuario['nombre_usuario'] ?? '';
                
                debug_to_file("Login exitoso - Redirigiendo a index.php");
                header("Location: ../index.php");
                exit;
            } else {
                // Verificar si la contraseña podría no estar hasheada
                debug_to_file("Verificando si la contraseña no está hasheada...");
                if ($password === $stored_password) {
                    debug_to_file("¡ADVERTENCIA! Las contraseñas coinciden sin hash. La seguridad está comprometida.");
                    
                    // Aunque no es seguro, permitimos el login para fines de desarrollo
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['nombre_usuario'] = $usuario['nombre'] ?? $usuario['nombre_usuario'] ?? '';
                    
                    debug_to_file("Login exitoso (inseguro) - Redirigiendo a index.php");
                    header("Location: ../index.php");
                    exit;
                }
                
                debug_to_file("Contraseña incorrecta");
                $_SESSION['error_login'] = 'Email o contraseña incorrectos';
                header("Location: pages-login.php");
                exit;
            }
        } else {
            debug_to_file("Usuario no encontrado en la base de datos");
            $_SESSION['error_login'] = 'Email o contraseña incorrectos';
            header("Location: pages-login.php");
            exit;
        }
    } catch (Exception $e) {
        debug_to_file("ERROR: " . $e->getMessage());
        $_SESSION['error_login'] = 'Error de conexión con la base de datos: ' . $e->getMessage();
        header("Location: pages-login.php");
        exit;
    }
    
} else {
    debug_to_file("Acceso directo a login_process.php sin formulario POST");
    header("Location: pages-login.php");
    exit;
}

$conn->close();
debug_to_file("Conexión cerrada");