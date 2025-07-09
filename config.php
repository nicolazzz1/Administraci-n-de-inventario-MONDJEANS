<?php
// config.php

// Define tus credenciales de la base de datos
define('DB_SERVER', 'localhost');    // El servidor de la base de datos, usualmente 'localhost'
define('DB_USERNAME', 'root');       // Cambia esto a tu usuario de la base de datos (e.g., 'root' para XAMPP/WAMP por defecto)
define('DB_PASSWORD', '');           // Cambia esto a tu contraseña de la base de datos (vacío por defecto para XAMPP/WAMP sin contraseña)
define('DB_NAME', 'moonjeans');    // El nombre de tu base de datos

// Intentar establecer la conexión a la base de datos MySQL
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if($link === false){
    die("ERROR: No se pudo conectar a la base de datos. " . mysqli_connect_error());
}
?>