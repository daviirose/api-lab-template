<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response; //This allows us to use the Request and Response objects in our code

require './vendor/autoload.php'; //It allows you to automatically pull in scripts and classes without having to do it manually
$app = new \apilabtemplate\App; //$app is our instance of slim
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});
//{name} in the url indicates what the argument will be called
//Line 9; adds the text "Hello, $name" to the response

$container = $app->getContainer();
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
}; //This creates a new directory 'logs' in the firstSlim directory
//Creates and writes to app.log file in that directory.
$app->run(); //This tells PHP to run the slim app
