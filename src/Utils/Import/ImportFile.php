<?php

namespace App\Utils\Import;

class ImportFile
{
    private string $fileName;

    private string $extension;

    private string $directory;

    public function __construct(
        string $fileName,
        string $extension,
        string $directory,
    ) {
        $this->fileName = $fileName;
        $this->extension = $extension;
        $this->directory = $directory;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function getPath(): string
    {
        return $this->directory . $this->fileName . $this->extension;
    }
}