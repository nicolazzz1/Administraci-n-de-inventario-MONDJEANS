<?php
$conexion = new mysqli("localhost", "root", "", "moonjeans");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$accion = $_POST['accion'] ?? '';
$mensaje = "";
$exito = false;

switch ($accion) {
    case "agregar":
        $usuario = $_POST['usuario'];
        $nombre1 = $_POST['nombre1'];
        $nombre2 = $_POST['nombre2'];
        $apellido1 = $_POST['apellido1'];
        $apellido2 = $_POST['apellido2'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $contrasena = $_POST['contrasena'];
        $rol = $_POST['rol'];

        $sql = "INSERT INTO usuario 
            (Usuario, `Nombre 1`, `Nombre 2`, `Apellido 1`, `Apellido 2`, Direccion, Telefono, Contraseña, Rol)
            VALUES (?, ?, ?, ?, ?, ?, ?, AES_ENCRYPT(?, 'clave_secreta'), ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssiss", $usuario, $nombre1, $nombre2, $apellido1, $apellido2, $direccion, $telefono, $contrasena, $rol);

        if ($stmt->execute()) {
            $mensaje = "✅ Usuario agregado correctamente.";
            $exito = true;
        } else {
            $mensaje = "❌ Error al agregar usuario: " . $stmt->error;
        }
        $stmt->close();
        break;

    case "eliminar":
        $usuario = $_POST['usuario'];
        $sql = "DELETE FROM usuario WHERE Usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $usuario);

        if ($stmt->execute()) {
            $mensaje = "✅ Usuario eliminado correctamente.";
            $exito = true;
        } else {
            $mensaje = "❌ Error al eliminar usuario: " . $stmt->error;
        }
        $stmt->close();
        break;

    case "editar":
        $usuario = $_POST['usuario'];
        $nombre1 = $_POST['nombre1'];
        $nombre2 = $_POST['nombre2'];
        $apellido1 = $_POST['apellido1'];
        $apellido2 = $_POST['apellido2'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $rol = $_POST['rol'];

        $sql = "UPDATE usuario 
                SET `Nombre 1` = ?, `Nombre 2` = ?, `Apellido 1` = ?, `Apellido 2` = ?, Direccion = ?, Telefono = ?, Rol = ?
                WHERE Usuario = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssssiss", $nombre1, $nombre2, $apellido1, $apellido2, $direccion, $telefono, $rol, $usuario);

        if ($stmt->execute()) {
            $mensaje = "✅ Datos del usuario actualizados.";
            $exito = true;
        } else {
            $mensaje = "❌ Error al actualizar usuario: " . $stmt->error;
        }
        $stmt->close();
        break;

    default:
        $mensaje = "❌ Acción inválida.";
        break;
}

$conexion->close();
?>

<!-- HTML unificado para mostrar el resultado con estilo -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resultado</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f2f5;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .resultado {
      background-color: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
      max-width: 500px;
    }

    .resultado h2 {
      color: <?= $exito ? "'#28a745'" : "'#dc3545'" ?>;
      font-size: 20px;
    }

    .btn {
      margin-top: 20px;
      padding: 12px 25px;
      border: none;
      border-radius: 25px;
      background-color: #007bff;
      color: white;
      font-weight: bold;
      text-decoration: none;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="resultado">
    <h2><?= $mensaje ?></h2>
    <a href="cuentaypermisos.html" class="btn">← Volver al inicio</a>
  </div>
</body>
</html>
