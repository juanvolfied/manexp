<?php
namespace App\Http\Controllers;

//use Spatie\Backup\Tasks\Backup\BackupJob;
//use Illuminate\Support\Facades\Artisan;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use ZipArchive;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }    
    public function mostrarBackupForm()
    {
        $backupFiles = collect(Storage::files('backups'))
            ->filter(function ($file) {
                return Str::endsWith($file, '.zip');
            })
            ->sortByDesc(function ($file) {
                return Storage::lastModified($file);
            })
            ->take(10)
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'size' => Storage::size($file),
                    'date' => date('Y-m-d H:i:s', Storage::lastModified($file)),
                    'url' => route('backup.descargar', ['filename' => basename($file)]),
                ];
            });

        return view('mantenimiento.backup', compact('backupFiles'));
    }
    public function generate()
    {
        /*$dbName = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');*/
        $host     = config('database.connections.mysql.host');
        $port     = config('database.connections.mysql.port');
        $dbName   = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $timestamp = date('Y-m-d_H-i-s');
        $sqlFilename = "backup-{$timestamp}.sql";
        $zipFilename = "backup-{$timestamp}.zip";

        $backupPath = storage_path("app/backups");
        $sqlFilePath = "{$backupPath}/{$sqlFilename}";
        $zipFilePath = "{$backupPath}/{$zipFilename}";

        // Crear directorio si no existe
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        // Ejecutar el comando mysqldump
//        $command = "mysqldump --user={$username} --password=\"{$password}\" --host={$host} --port={$port} {$dbName} > {$sqlFilePath}";
//        $process = Process::fromShellCommandline($command);
//        $process->run();

//        if (!$process->isSuccessful()) {
            /*return response()->json([
                'message' => 'Error al generar el backup SQL',
                'error' => $process->getErrorOutput(),
            ], 500);*/
//            return redirect()->back()->with('error', 'ERROR AL GENERAR BACKUP: ' . $process->getErrorOutput());
//        }

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($dbName)
        );
        $process = Process::fromShellCommandline($command);
        $process->run();
        if (!$process->isSuccessful()) {
            //throw new \RuntimeException("Backup fallido: " . $process->getErrorOutput());
            return redirect()->back()->with('error', 'ERROR AL GENERAR BACKUP: ' . $process->getErrorOutput());
        }
        file_put_contents($sqlFilePath, $process->getOutput());




        // Crear ZIP
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($sqlFilePath, $sqlFilename);
            $zip->close();
            chmod($zipFilePath, 0775);
        } else {
            //return response()->json(['message' => 'Error al crear el archivo ZIP'], 500);
            return redirect()->back()->with('error', 'Error al crear el archivo ZIP: ');
        }

        // Eliminar el archivo .sql temporal
        unlink($sqlFilePath);

        // Descargar y eliminar el ZIP después de enviar
        //return response()->download($zipFilePath)->deleteFileAfterSend();
        return redirect()->back()->with('success', 'BACKUP GENERADO SATISFACTORIAMENTE '. $zipFilename);
    }
    /*
    public function generate()
    {
        try {
            Artisan::call('backup:run', [
                '--only-db' => true,
            ]);

            return redirect()->back()->with('success', 'BACKUP GENERADO.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ERROR AL GENERAR BACKUP: ' . $e->getMessage());
        }
    }
    */
}
