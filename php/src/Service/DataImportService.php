<?php

namespace Oscar\Service;

use Oscar\Classes\FileReaderInterface;

class DataImportService
{
    public function __construct(private FileReaderInterface $fileReader)
    {
    }

    public function import($filePath): void
    {
        $importDataArray = $this->fileReader->readFile($filePath);
        print_r($importDataArray);
        die;
    }
}