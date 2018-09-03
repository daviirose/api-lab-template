<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response; //This allows us to use the Request and Response objects in our code

require './vendor/autoload.php'; //It allows you to automatically pull in scripts and classes without having to do it manually
$app = new \Slim\App; //$app is our instance of slim.
