<?php

require '../vendor/autoload.php';
use Oscar\Config\Database;

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();
$dbConnection = (new Database())->connect();
