<?php
$conexion = new mysqli("localhost", "root", "", "moonjeans");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$accion = $_REQUEST['accion'] ?? '';

switch ($accion) {
    case 'listar':
        $resultado = $conexion->query("SELECT * FROM proveedores");
        $proveedores = [];
        while ($row = $resultado->fetch_assoc()) {
            $proveedores[] = $row;
        }
        echo json_encode($proveedores);
        break;

    case 'agregar':
        $nombre = $_POST['nombre'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $tipo = $_POST['tipoProveedor'] ?? '';
        
        $stmt = $conexion->prepare("INSERT INTO proveedores (Nombre, Direccion, TipoProveedor) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $direccion, $tipo);
        $stmt->execute();
        echo "✅ Proveedor agregado";
        break;

    case 'editar':
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $tipo = $_POST['tipoProveedor'] ?? '';
        
        $stmt = $conexion->prepare("UPDATE proveedores SET Nombre=?, Direccion=?, TipoProveedor=? WHERE idProveedores=?");
        $stmt->bind_param("sssi", $nombre, $direccion, $tipo, $id);
        $stmt->execute();
        echo "✅ Proveedor actualizado";
        break;

    case 'eliminar':
        $id = $_POST['id'] ?? 0;
        $stmt = $conexion->prepare("DELETE FROM proveedores WHERE idProveedores=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "✅ Proveedor eliminado";
        } else {
            echo "⚠️ No se pudo eliminar. Verifica si tiene compras relacionadas.";
        }
        break;
}

$conexion->close();
?>
