<?php


ini_set('display_errors', 1);

require_once '../../bootstrap/app.php';
require_once '../../Config/Database.php';

use Oscar\Config\Database;
use Oscar\Models\Car;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new Database();
$connection = $db->connect();
/**
 * End point to retrieve all cars
 * params supported are page and limit for paginated response (default page = 1, limit = 10)
 * if limit and page is not passed in query params or if both params are not integer then default params applied
 */
$limit = 10;
$page = 1;
if (isset($_GET['limit'])) {
    $limit = ((int)$_GET['limit']) !== 0 ? (int)$_GET['limit'] : 10;
}
if (isset($_GET['page'])) {
    $page = ((int)$_GET['page']) !== 0 ? (int)$_GET['page'] : 1;
}
$carModel = new Car($connection);
$carResponse = $carModel->getAll($limit, $page);
http_response_code($carResponse['code']);
echo json_encode(
    ['data' => $carResponse['data'], 'status' => $carResponse['status'], 'message' => $carResponse['message']]
);
