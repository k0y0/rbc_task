<?php

namespace App\Service\Import\FileImport\Importers;

use App\Service\Import\FileImport\Types\MedicineOrderDataImportInterface;
use App\Utils\Import\ImportFile;
use App\Utils\Import\MedicineOrder\MedicineOrderData;

class LdifImporter implements MedicineOrderDataImportInterface
{
    const EXTENSION = '.ldif';

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

        while (($line = fgets($fh)) !== false) {
            $read = trim($line);
            if (empty($read)) {
                if (
                    $customer ||
                    $country ||
                    $order ||
                    $status ||
                    $group
                ) {
                    $data[] = new MedicineOrderData($customer, $country, $order, $status, $group, ltrim($this->importFile->getExtension(), '.'));
                }
                $customer = null;
                $country = null;
                $order = null;
                $status = null;
                $group = null;
                continue;
            }

            $lineParts = explode(':' ,$read);
            switch (strtolower($lineParts[0])) {
                case 'customer':
                    $customer = $lineParts[1];
                    break;
                case 'country':
                    $country = $lineParts[1];
                    break;
                case 'order':
                    $order = $lineParts[1];
                    break;
                case 'status':
                    $status = $lineParts[1];
                    break;
                case 'group':
                    $group = $lineParts[1];
                    break;
            }
        }

        fclose($fh);
        return $data;
    }
}