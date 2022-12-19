<?php

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteOldZipFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:oldzipfiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete old zip files from the public/zip directory";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get the current time
        $now = Carbon::now();

        // Get the path to the zip directory
        $zipDirectory = public_path('zip');

        // Get all the files in the zip directory
        $files = File::files($zipDirectory);

        // Loop through each file
        foreach ($files as $file) {
            // Get the last modified time of the file
            $lastModified = Carbon::createFromTimestamp(File::lastModified($file));

            // Check if the file is older than one day
            if ($now->diffInDays($lastModified) > 1) {
                // Delete the file if it is older than one day
                File::delete($file);
            }
        }
    }
}
