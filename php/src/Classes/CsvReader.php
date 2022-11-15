<?php

namespace Oscar\Classes;

class CsvReader implements FileReaderInterface
{

    public function readFile($input): array
    {
        $csvArray = [];
        $csvHandle = fopen($input, 'r');
        $csvHeader = fgetcsv($csvHandle);
        while (($data = fgetcsv($csvHandle, null, ',')) !== false) {
            $row = [];
            foreach ($data as $key => $value) {
                $row[$csvHeader[$key]] = $value ?: null;
            }
            $csvArray[] = $row;
        }
        return $csvArray;
    }
}