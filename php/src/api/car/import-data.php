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
        $dataImport = new DataImportService($reader);
        $dataImport->import($_FILES['file']['tmp_name']);
    }
    die;
}