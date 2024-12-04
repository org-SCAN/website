<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class LogViewer
{
    protected $logPath;

    public function __construct(
    )
    {
        $this->logPath = storage_path('logs/api');
    }

    public function getLogs()
    {
        $logFiles = File::files($this->logPath);

        $logs = collect();

        foreach ($logFiles as $fileIndex => $file) {
            // Vérifier que le fichier est un log API
            if (strpos($file->getFilename(),
                    'api') === false) {
                continue;
            }

            $lines = file($file->getRealPath());

            foreach ($lines as $index => $line) {
                $logEntry = $this->getLine($line);
                if ($logEntry) {
                    $logEntry['index'] = $index;
                    $logEntry['file_index'] = $fileIndex;
                    $logEntry['file_name'] = $file->getFilename();
                    $logs->push($logEntry);
                }
            }
        }

        return $logs->sortByDesc('datetime');
    }

    protected function getLine(
        $line
    )
    {
        if (empty($line)) {
            return null;
        }

        // Décoder directement la ligne en JSON
        $json = json_decode($line,
            true);
        if (!$json) {
            // Si le JSON n'est pas valide, gérer l'erreur ici
            return null;
        }

        // Extraire les informations nécessaires
        return [
            'datetime' => Carbon::parse($json['datetime'] ?? $json['time'] ?? ''),
            'level' => $json['level_name'] ?? $json['level'] ?? '',
            'message' => $json['message'] ?? '',
            'context' => $json['context'] ?? [],
        ];
    }
}
