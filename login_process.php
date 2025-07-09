<?php
// login_process.php
session_start(); // Inicia la sesión PHP

// Incluir el archivo de configuración de la base de datos
// Este 'require_once' asume que config.php está en la misma carpeta que login_process.php
require_once 'config.php';

// Definir variable para almacenar mensajes de error de inicio de sesión
$login_err = "";

// Definir la ruta base de tu proyecto para las redirecciones
// ¡ESTA ES LA RUTA CRÍTICA QUE DEBE COINCIDIR CON TU CARPETA EN HTDOCS!
$base_path = "/proyectoxampp/"; // <--- ¡CAMBIADO!

// Procesar solo si el formulario fue enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar y sanear los datos del formulario (usando '??' para evitar warnings si una clave no existe)
    $username = trim($_POST["usuario"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $rol = trim($_POST["rol"] ?? '');

    // Verificaciones básicas de campos vacíos
    if (empty($username)) {
        $login_err = "Por favor, ingresa tu usuario.";
    } elseif (empty($password)) {
        $login_err = "Por favor, ingresa tu contraseña.";
    } elseif (empty($rol)) {
        $login_err = "Por favor, selecciona un rol.";
    }

    // Si no hay errores de validación inicial, proceder con la consulta a la BD
    if (empty($login_err)) {
        // Preparar una sentencia SELECT para buscar al usuario por nombre y rol
        // Los campos de la tabla en tu base de datos son: 'idUsuario', 'nombre', 'contraseña', 'rol'
       $sql = "SELECT idUsuario, Usuario, Contraseña, Rol FROM usuario WHERE Usuario = ? AND Rol = ?";


        if ($stmt = mysqli_prepare($link, $sql)) {
            // Vincular variables a la sentencia preparada como parámetros
            // "ss" indica que ambos parámetros (nombre y rol) son strings
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_rol);

            // Establecer los parámetros con los valores del formulario
            $param_username = $username;
            $param_rol = $rol;

            // Intentar ejecutar la sentencia preparada
            if (mysqli_stmt_execute($stmt)) {
                // Almacenar el resultado de la consulta
                mysqli_stmt_store_result($stmt);

                // Verificar si se encontró exactamente un usuario con ese nombre y rol
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Vinculamos idUsuario a una variable local ($temp_idUsuario)
                    mysqli_stmt_bind_result($stmt, $temp_idUsuario, $db_username, $db_password_hash, $db_rol);
                    if (mysqli_stmt_fetch($stmt)) {
                        // COMPARAMOS DIRECTAMENTE EL TEXTO PLANO (NO RECOMENDADO EN PRODUCCIÓN)
                        if ($password === $db_password_hash) {
                            // Contraseña correcta: iniciar la sesión del usuario
                            $_SESSION["loggedin"] = true;
                            // Puedes guardar el ID del usuario si lo necesitas más tarde, aunque no venga del formulario
                            // $_SESSION["id"] = $temp_idUsuario;
                            $_SESSION["username"] = $db_username;
                            $_SESSION["rol"] = $db_rol; // Guarda el rol en la sesión

                            // Redirigir al usuario al dashboard correspondiente
                            if ($db_rol === "administrador") {
                                header("location: " . $base_path . "aministrador.html");
                            } else {
                                header("location: " . $base_path . "usuario.html");
                            }
                            exit(); // ¡Crucial para detener la ejecución del script después de la redirección!
                        } else {
                            // Contraseña incorrecta
                            $login_err = "Contraseña incorrecta.";
                        }
                    }
                } else {
                    // No se encontró un usuario con ese nombre y rol
                    $login_err = "Usuario o rol incorrecto.";
                }
            } else {
                // Error al ejecutar la sentencia
                $login_err = "Oops! Algo salió mal al ejecutar la consulta. Por favor, inténtalo de nuevo más tarde.";
            }

            // Cerrar la sentencia preparada
            mysqli_stmt_close($stmt);
        } else {
            // Error al preparar la consulta SQL
            $login_err = "Error interno del servidor al preparar consulta. Intenta de nuevo más tarde.";
        }
    }

    // Si llegamos aquí, significa que hubo un error de login (ya sea por validación inicial o BD).
    // Redirige de vuelta a roles.html (tu página principal) y pasa el error como un parámetro GET en la URL.
    header("location: " . $base_path . "roles.html?login_error=" . urlencode($login_err));
    exit(); // ¡Crucial para detener la ejecución!
}

// Cerrar la conexión a la base de datos (solo si no hubo redirección)
// Esta línea solo se ejecutará si el script no sale con exit() antes.
mysqli_close($link);
?>