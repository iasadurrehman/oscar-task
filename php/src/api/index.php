<?php
ini_set('display_errors', 1);
define('BASE_PATH', dirname(__DIR__, 1));
require_once BASE_PATH . '/bootstrap/app.php';
use Oscar\Controller\Car;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
print_r($uri);

// endpoints starting with `/post` or `/posts` for GET shows all posts
// everything else results in a 404 Not Found
if ($uri[2] !== 'car') {
    if($uri[2] !== 'cars'){
        header("HTTP/1.1 404 Not Found");
        exit();
    }
}

// endpoints starting with `/posts` for POST/PUT/DELETE results in a 404 Not Found
if ($uri[2] == 'cars' and isset($uri[3])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// the post id is, of course, optional and must be a number
$postId = null;
if (isset($uri[3])) {
    $postId = (int) $uri[3];
}
//
$requestMethod = $_SERVER["REQUEST_METHOD"];
$controller = new Car($dbConnection);