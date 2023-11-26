<?php

namespace App\Service\Import\FileImport\Types;

use App\Utils\Import\MedicineOrder\MedicineOrderData;

interface MedicineOrderDataImportInterface
{
    /**
     * @return MedicineOrderData[]
     */
    public function importMedicineOrderData(): array;
}