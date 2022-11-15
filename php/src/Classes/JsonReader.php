<?php

namespace Oscar\Classes;

class JsonReader implements FileReaderInterface
{

    public function readFile($input) : array
    {
        $file = file_get_contents($input);
        return json_decode($file,1) ?? [];
    }
}