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
        $this->logPath = storage_path('logs');
    }

    public function getLogs(
        $date = null
    ) {
        if ($date) {
            $filename = "api-{$date}.log";
        } else {
            $filename = "api-".now()->format("Y-m-d").".log";
        }

        $path = $this->logPath.DIRECTORY_SEPARATOR.$filename;

        if (!File::exists($path)) {
            return collect();
        }

        $logs = collect(file($path))->map(function (
            $line
        ) {
            return $this->parseLine($line);
        })->filter();

        return $logs->sortByDesc('datetime');
    }

    public function getAvailableDates(
    )
    {
        return collect(File::files($this->logPath))->map(function (
            $file
        ) {
            return str_replace('api-',
                '',
                str_replace('.log',
                    '',
                    $file->getFilename()));
        })->sort()->reverse();

    }

    protected function getLine(
        $line
    ) {

        if (empty($line)) {
            return null;
        }

        $json = json_decode($line,
            true);

        if (!$json) {
            return null;
        }

        return [
            'datetime' => Carbon::parse($json['datetime'] ?? ''),
            'level' => $json['level'] ?? '',
            'message' => $json['message'] ?? '',
            'context' => $json['context'] ?? [],
        ];

    }

}
