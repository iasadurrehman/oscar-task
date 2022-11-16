<?php

use Oscar\Classes\JsonReader;
use Oscar\Config\Database;
use Oscar\Models\Car;
use Oscar\Service\DataImportService;

ini_set('display_errors', 1);

require_once '../../bootstrap/app.php';
require_once '../../Config/Database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new Database();
$connection = $db->connect();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $carId = 0;
    if (isset($_GET['car_id'])) {
        $carId = (int)$_GET['car_id'];
    }
    if ($carId) {
        $carModel = new Car($connection);
        $carResponse = $carModel->find($carId);
        http_response_code($carResponse['code']);
        echo json_encode(
            ['data' => $carResponse['data'], 'status' => $carResponse['status'], 'message' => $carResponse['message']]
        );
    } else {
        http_response_code(400);
        echo json_encode(
            ['data' => null, 'status' => false, 'message' => 'Enter valid car id']
        );
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reader = new JsonReader();
    $inputJSON = $reader->readFile('php://input');
    $importService = new DataImportService($reader, $connection);
    $status = $importService->import($inputJSON);
    http_response_code($status['code']);
    echo json_encode($status);
}
