<?php

define('BASE_PATH', dirname(__DIR__, 1));
require BASE_PATH . '/vendor/autoload.php';

use Oscar\Config\Database;

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();
$dbConnection = (new Database())->connect();
