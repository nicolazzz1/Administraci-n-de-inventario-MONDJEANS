<?php
$servername = "localhost"; // Suponiendo que su servidor MySQL está en localhost
$username = "root";       // Su nombre de usuario de MySQL
$password = "";           // Su contraseña de MySQL (vacía si no tiene)
$dbname = "moonjeans";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejar diferentes acciones
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            addProveedor($conn);
            break;
        case 'edit':
            editProveedor($conn);
            break;
        case 'delete':
            deleteProveedor($conn);
            break;
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'fetch') {
    fetchProveedores($conn);
} else {
    // Acción por defecto
}

$conn->close();

function addProveedor($conn) {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $tipoProveedor = $_POST['tipoProveedor'];

    $sql = "INSERT INTO provedores (Nombre, Direccion, TipoDeProvedor) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $direccion, $tipoProveedor);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => '¡Proveedor agregado exitosamente!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al agregar proveedor: ' . $stmt->error]);
    }
    $stmt->close();
}

function editProveedor($conn) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $tipoProveedor = $_POST['tipoProveedor'];

    $sql = "UPDATE provedores SET Nombre = ?, Direccion = ?, TipoDeProvedor = ? WHERE IdProovedores = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $direccion, $tipoProveedor, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => '¡Proveedor actualizado exitosamente!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar proveedor: ' . $stmt->error]);
    }
    $stmt->close();
}

function deleteProveedor($conn) {
    $id = $_POST['id'];

    $sql = "DELETE FROM provedores WHERE IdProovedores = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => '¡Proveedor eliminado exitosamente!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar proveedor: ' . $stmt->error]);
    }
    $stmt->close();
}

function fetchProveedores($conn) {
    $sql = "SELECT IdProovedores, Nombre, Direccion, TipoDeProvedor FROM provedores";
    $result = $conn->query($sql);

    $proveedores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $proveedores[] = $row;
        }
    }
    echo json_encode($proveedores);
}
?>