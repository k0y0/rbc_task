<?php

namespace App\Service\Import;

use App\Service\Import\FileImport\Factory\FileImportFactory;
use App\Utils\Import\ImportFile;
use App\Utils\Import\ImportResults;

class DataImportService
{
    protected const EXCLUDED_FILES = [
        '.',
        '..',
        'tmp'
    ];

    public function __construct(
        private readonly string $filesDir,
        private readonly FileImportFactory $fileImportFactory,
    ) {}

    /**
     * get import files
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getImportFiles(): array
    {
        if (!is_dir($this->filesDir)) {
            throw new \Exception('Invalid directory');
        }

        $files = scandir($this->filesDir);

        return array_filter($files, function ($fileName){
            return !in_array($fileName, self::EXCLUDED_FILES);
        });
    }

    public function importData(array $filesSelected)
    {
        $importFilesArray = [];
        foreach ($filesSelected as $file) {
            $importFile = $this->createImportFileFromFileName($file, $this->filesDir);
            $importFilesArray[] = $importFile;
        }

        $results = [];
        foreach ($importFilesArray as $importFile) {
            $importer = $this->fileImportFactory->createMedicineImport($importFile);
            $data = $importer->importMedicineOrderData();
            $results = array_merge($results, $data);
        }
        return $results;
    }

    private function createImportFileFromFileName(string $fullFilename, string $directory): ImportFile
    {
        $fileNameParts = explode('.', $fullFilename);
        $fileName = $fileNameParts[0];
        $extension = '.' . $fileNameParts[1];

        return new ImportFile($fileName, $extension, $directory);
    }
}