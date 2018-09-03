<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response; //This allows us to use the Request and Response objects in our code

require './vendor/autoload.php'; //It allows you to automatically pull in scripts and classes without having to do it manually
$app = new \Slim\App; //$app is our instance of slim
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});
//{name} in the url indicates what the argument will be called
//Line 9; adds the text "Hello, $name" to the response
$app->run(); //This tells PHP to run the slim app
