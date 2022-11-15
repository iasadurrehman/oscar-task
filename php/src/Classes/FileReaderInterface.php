<?php

namespace Oscar\Classes;

interface FileReaderInterface
{
    public function readFile($input) : array;
}