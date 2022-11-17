<?php

namespace Oscar\Classes;

class JsonReader implements FileReaderInterface
{

    /**
     * Parse JSON and convert ot equivalent associative array
     * @param $input
     * @return array
     */
    public function readFile($input): array
    {
        $file = '';
        if ($input !== null) {
            $file = file_get_contents($input);
        }
        return json_decode($file, 1) ?? [];
    }
}