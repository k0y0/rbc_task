<?php

namespace App\Service\Import\FileImport\Factory;

use App\Service\Import\FileImport\Exception\NotSupportedFileTypeException;
use App\Service\Import\FileImport\Importers\CsvImporter;
use App\Service\Import\FileImport\Importers\JsonImporter;
use App\Service\Import\FileImport\Importers\LdifImporter;
use App\Service\Import\FileImport\Types\MedicineOrderDataImportInterface;
use App\Utils\Import\ImportFile;

class FileImportFactory
{

    /**
     * @param ImportFile $file
     *
     * @return MedicineOrderDataImportInterface
     *
     * @throws NotSupportedFileTypeException
     */
    public function createMedicineImport(ImportFile $file): MedicineOrderDataImportInterface
    {
        switch ($file->getExtension()) {
            case CsvImporter::EXTENSION:
                return new CsvImporter($file);
            case JsonImporter::EXTENSION:
                return new JsonImporter($file);
            case LdifImporter::EXTENSION:
                return new LdifImporter($file);
            default:
                throw new NotSupportedFileTypeException();
        }
    }
}