<?php
// Aquí pones la contraseña que deseas encriptar
$password = "123456";  // Cambia esto por la contraseña que deseas encriptar

// Generar el hash de la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Mostrar el hash generado para que lo puedas copiar
echo "El hash de tu contraseña es: <br>";
echo $hashed_password;
?>
