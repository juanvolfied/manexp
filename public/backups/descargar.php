<?php
//$filename = 'backup-2025-10-12_09-16-01.zip'; // Cambia esto por el nombre de tu archivo
$filename = $_GET["nomarchivo"] . ".zip";
    if (ob_get_level()) {
        ob_end_clean();
    }

    // Configurar los headers para forzar descarga
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream'); // Tipo genérico seguro
    header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filename));

    // Lee y envía el archivo
    readfile($filename);
    exit;
    
    
if (file_exists($filename)) {
    if (unlink($filename)) {
        echo "Archivo '$filename' eliminado correctamente.";
    } else {
        echo "Error: No se pudo eliminar el archivo '$filename'.";
    }
} else {
    echo "Error: El archivo '$filename' no existe.";
}
?>