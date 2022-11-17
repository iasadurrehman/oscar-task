<?php

use Oscar\Classes\CsvReader;
use Oscar\Classes\JsonReader;
use Oscar\Config\Database;
use Oscar\Service\DataImportService;

ini_set('display_errors', 1);

require_once '../../bootstrap/app.php';
require_once '../../Config/Database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new Database();
$connection = $db->connect();

/**
 * If request method is POST
 * and file uploaded is of type JSON then factory calls for JSON reader, for csv, calls for CSVReader
 * Once the reader is found,
 * get the input file and pass to Dataimport service for processing and import
 * response with status provided
 */
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $reader = null;
    if (isset($_FILES['file']) && $_FILES['file']['type'] === 'application/json') {
        $reader = new JsonReader();
    } else {
        if (isset($_FILES['file']) && $_FILES['file']['type'] === 'text/csv') {
            $reader = new CsvReader();
        }
    }
    if (isset($reader)) {
        $dataImport = new DataImportService($reader, $connection);
        $transformedArray = $dataImport->read($_FILES['file']['tmp_name']);
        $status = $dataImport->import($transformedArray);
        http_response_code($status['code']);
        echo json_encode($status);
    }
}