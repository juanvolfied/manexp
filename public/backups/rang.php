<?php
date_default_timezone_set('America/Lima');

$host = 'localhost';
$usuario='root';
$contrasena='BLXYb1gFhVBqeiPUHek2';
$base_datos='mpfnarequipa_expedientes';

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT * FROM observacion_inventario";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
    /*
        echo "ID: " . $fila['id'] . "<br>";
        echo "Inventario: " . $fila['nro_inventario'] . "<br>";
        echo "FechaHora: " . $fila['fechahora'] . "<br>";
        echo "Observación: " . $fila['observacion'] . "<hr>";
        */
        
        $nroinv=$fila['nro_inventario'];
        $sql2 = "SELECT distinct nro_inventario, fecha_inventario, hora_inventario FROM ubicacion_exp where nro_inventario='".$nroinv."' and motivo_movimiento='Inventario' ";
	$res = $conexion->query($sql2);
	if ($res && $res->num_rows > 0) {
	    $row = $res->fetch_assoc();

		$fecha=$row["fecha_inventario"];
		$hora=$row["hora_inventario"];
	echo $nroinv." - ".$fecha." ".$hora."<br>";

		$sql3 = "update observacion_inventario set fechahora='".$fecha." ".$hora."' where nro_inventario='".$nroinv."'  ";
		$conexion->query($sql3);
	}

    }
} else {
    echo "No hay registros";
}

$conexion->close();

?>
