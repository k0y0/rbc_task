<?php

namespace App\Service\Import\FileImport\Importers;

use App\Service\Import\FileImport\Types\MedicineOrderDataImportInterface;
use App\Utils\Import\ImportFile;
use App\Utils\Import\MedicineOrder\MedicineOrderData;

class CsvImporter implements MedicineOrderDataImportInterface
{
    const EXTENSION = '.csv';

    const SEPARATOR = '|';

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

        while (($rawData = fgetcsv($fh, null, self::SEPARATOR)) !== false) {
            if (($rawData !== false) && $this->checkIsHeader($rawData)) {
                continue;
            }
            $medicineOrderData = new MedicineOrderData(
                $rawData[0],
                $rawData[1],
                $rawData[2],
                $rawData[3],
                $rawData[4],
                ltrim($this->importFile->getExtension(), '.')
            );
            $data[] = $medicineOrderData;
        }

        return $data;
    }

    private function checkIsHeader(array $rawData): bool
    {
        if ($rawData[0] === 'Customer') {
            return true;
        }
        return false;
    }
}