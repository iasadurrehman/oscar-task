<?php

use Oscar\Config\Database;
use Oscar\Models\Car;
use Oscar\Models\CarMeta;

ini_set('display_errors', 1);

require_once '../../bootstrap/app.php';
require_once '../../Config/Database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
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
        $carMeta = new CarMeta($connection);
        $carResponse = $carMeta->findByCarId($carId);
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
