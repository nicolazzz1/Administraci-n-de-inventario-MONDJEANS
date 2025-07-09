<?php
require_once 'conexion.php';

$cnn = Conexion::getConexion();

if ($cnn) {
    echo "Conexión exitosa con la base de datos.";
} else {
    echo " No se pudo conectar.";
}
?>
