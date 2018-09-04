<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response; //This allows us to use the Request and Response objects in our code

require './vendor/autoload.php'; //It allows you to automatically pull in scripts and classes without having to do it manually

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = 'root';
$config['db']['dbname'] = 'apidb';
//db

$app = (new Davian\api\App($db))->get(); //$app is our instance of slim $app = (new davian\firstSlim\App($db))->get();
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
//{name} in the url indicates what the argument will be called
  $name = $args['name'];
  $response->getBody()->write("Hello, $name"); //Line 15; adds the text "Hello, $name" to the response
    return $response;
});

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $this->logger->addInfo('get request to /hello/'.$name);
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->get('/cars', function (Request $request, Response $response) {
    $this->logger->addInfo("GET /cars");
    $cars = $this->db->query('SELECT * from cars')->fetchAll();
    $jsonResponse = $response->withJson($cars);
    return $jsonResponse;
}); //First Endpoint (Get cars)


$container = $app->getContainer();
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
}; //This creates a new directory 'logs' in the api-lab-template directory
//Creates and writes to app.log file in that directory.

$app->run(); //This tells PHP to run the slim app
