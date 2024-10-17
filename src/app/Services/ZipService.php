<?php

namespace App\Services;

use ZipArchive;

class ZipService
{
    protected $zip;

    public function __construct()
    {
        $this->zip = new ZipArchive();
    }

    public function open($filename, $flags)
    {
        return $this->zip->open($filename, $flags);
    }

    public function addFromString($localname, $contents)
    {
        return $this->zip->addFromString($localname, $contents);
    }

    public function close()
    {
        return $this->zip->close();
    }
}
