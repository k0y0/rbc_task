<?php

namespace App\Service\Import\FileImport\Importers;

use App\Service\Import\FileImport\Types\MedicineOrderDataImportInterface;
use App\Utils\Import\ImportFile;
use App\Utils\Import\MedicineOrder\MedicineOrderData;

class JsonImporter implements MedicineOrderDataImportInterface
{
    const EXTENSION = '.json';

    private ImportFile $importFile;

    public function __construct(
        ImportFile $importFile
    ) {
        $this->importFile = $importFile;
    }

    public function importMedicineOrderData(): array
    {
        $data = [];
        $fh = fopen($this->importFile->getPath(), 'r');

        $jsonData = json_decode(fread($fh, filesize($this->importFile->getPath())), true, 2048);
        foreach ($jsonData['data'] as $row) {
            $data[] = new MedicineOrderData(
                $row[0],
                $row[1],
                $row[2],
                $row[3],
                $row[4],
                ltrim($this->importFile->getExtension(), '.')
            );
        }
        return $data;
    }
}