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





$sql = "SELECT * FROM librocargos WHERE IFNULL(activo, '') = '' ";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
    	$feca=$fila['fechacargo'];
    	$idfi=$fila['id_fiscal'];
    	
    	
        //$sql2 = "SELECT codescrito FROM `libroescritos` where fecharegistro='".$feca."' and id_fiscal='".$idfi."' limit 1";
	$sql2 = "
	SELECT EXISTS (
	    SELECT 1
	    FROM libroescritos
	    WHERE fecharegistro >= '".$feca." 00:00:00'
	      AND fecharegistro <  '".$feca." 23:59:59'
	      AND id_fiscal = '".$idfi."'
	) AS existe";
	$res = $conexion->query($sql2);
	$row = $res->fetch_assoc();
	if ($row['existe']) {
		$sql3 = "update librocargos set activo='S' where fechacargo='".$feca."' and id_fiscal='".$idfi."'  ";
	} else {
		$sql3 = "update librocargos set activo='N' where fechacargo='".$feca."' and id_fiscal='".$idfi."'  ";
	}
	$conexion->query($sql3);
	echo $sql3."<br>";
    }
}
echo "FIN";

exit();

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
