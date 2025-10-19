<?php
date_default_timezone_set('America/Lima');
/*
// Configuración
$host     = '127.0.0.1';//'localhost';        // o la IP del servidor MySQL
$usuario  = 'laravel';
$contrasena = 'minpub@2025';
$base_datos = 'mpfnarequipa_expedientes';
*/
$host = 'localhost';
$usuario='root';
$contrasena='BLXYb1gFhVBqeiPUHek2';
$base_datos='mpfnarequipa_expedientes';

echo "Usuario que ejecuta el script: " . exec('whoami') . "\n";

// Ruta del archivo de salida
$fecha = date('Y-m-d_H-i-s');
$directorio_backup = __DIR__;  // ruta del directorio donde está este archivo PHP
$nombre_archivo = "{$directorio_backup}/backup-{$fecha}.sql";
//$nombre_archivo = "backup-{$fecha}.sql";        
//$ruta_archivo = "/ruta/donde/guardar/$nombre_archivo"; // Asegúrate que PHP tenga permisos de escritura aquí
$ruta_archivo = "$nombre_archivo"; // Asegúrate que PHP tenga permisos de escritura aquí

$nombre_zip = "{$directorio_backup}/backup-{$fecha}.zip";
$ruta_zip = "$nombre_zip";

// Comando mysqldump
$comando = "mysqldump --user={$usuario} --password={$contrasena} --host={$host} {$base_datos} > {$ruta_archivo}";

// Ejecutar el comando
exec($comando, $output, $resultado);

if ($resultado !== 0) {
    die("Error al crear el backup SQL. Código de salida: $resultado\n");
}

// 2. Comprimir el archivo SQL a ZIP
$zip = new ZipArchive();
if ($zip->open($ruta_zip, ZipArchive::CREATE) === TRUE) {
    $zip->addFile($ruta_archivo, $nombre_archivo);
    $zip->close();
    // 3. Eliminar el archivo SQL sin comprimir para ahorrar espacio
    unlink($ruta_archivo);
    chmod($ruta_zip, 0777);

    //echo "Backup comprimido creado exitosamente: $ruta_zip\n";
} else {
    //echo "No se pudo crear el archivo ZIP.\n";
}

?>
