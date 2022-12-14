<?php

namespace Oscar\Classes;

class CsvReader implements FileReaderInterface
{

    /**
     * Parse csv and make equivalent associative array
     * @param $input
     * @return array
     */
    public function readFile($input): array
    {
        $csvArray = [];
        if ($input !== null) {
            $csvHandle = fopen($input, 'r');
            $csvHeader = fgetcsv($csvHandle);
            while (($data = fgetcsv($csvHandle, null, ',')) !== false) {
                $row = [];
                foreach ($data as $key => $value) {
                    $row[$csvHeader[$key]] = $value ?: null;
                }
                $csvArray[] = $row;
            }
            fclose($csvHandle);
        }
        return $csvArray;
    }
}