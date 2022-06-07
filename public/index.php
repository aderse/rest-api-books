<?php
require "../bootstrap.php";
use Src\Controller\BookController;
global $dbConnection;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// All of our endpoints start with /book
// Everything else results in a 404 Not Found
if ($uri[1] !== 'book') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// the book id is, of course, optional and must be a number:
$bookId = null;
if (isset($uri[2])) {
    $bookId = (int) $uri[2];
}

if (isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] == getenv("API_NAME") &&  $_SERVER['PHP_AUTH_PW'] == getenv("API_PASS")) {
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $controller = new BookController($dbConnection, $requestMethod, $bookId);
    $controller->processRequest();
} else {
    header("HTTP/1.1 401 Unauthorized");
    exit('Unauthorized');
}
