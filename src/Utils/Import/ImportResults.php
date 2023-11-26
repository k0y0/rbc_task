<?php

namespace App\Utils\Import;

class ImportResults
{
    private ImportFile $importFile;

    private array $result;

    public function __construct(
        ImportFile $importFile,
        array $result
    ) {
        $this->importFile = $importFile;
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }
}