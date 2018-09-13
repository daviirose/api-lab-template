<?php
namespace Davian\api;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require './vendor/autoload.php';

class App
{

 private $app;
 public function __construct($db) {

   $config['db']['host']   = 'localhost';
   $config['db']['user']   = 'root';
   $config['db']['pass']   = 'root';
   $config['db']['dbname'] = 'apidb';
//db

   $app = new \Slim\App(['settings' => $config]);

   $container = $app->getContainer();
   $container['db'] = $db;

   $container['logger'] = function($c) {
       $logger = new \Monolog\Logger('my_logger');
       $file_handler = new \Monolog\Handler\StreamHandler('./logs/app.log');
       $logger->pushHandler($file_handler);
       return $logger;
   };

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
   });
   $app->get('/cars/{id}', function (Request $request, Response $response, array $args) {
       $id = $args['id'];
       $this->logger->addInfo("GET /cars/".$id);
       $car = $this->db->query('SELECT * from cars where id='.$id)->fetch();
       $jsonResponse = $response->withJson($car);

       return $jsonResponse;
   }); //First endpoint (get car by id)

   $app->put('/cars/{id}', function (Request $request, Response $response, array $args) {
       $id = $args['id'];
       $this->logger->addInfo("PUT /cars/".$id);

       // build query string
       $updateString = "UPDATE cars SET ";
       $fields = $request->getParsedBody();
       $keysArray = array_keys($fields);
       $last_key = end($keysArray);
       foreach($fields as $field => $value) {
         $updateString = $updateString . "$field = '$value'";
         if ($field != $last_key) {
           // conditionally add a comma to avoid sql syntax problems
           $updateString = $updateString . ", ";
         }
       }
       $updateString = $updateString . " WHERE id = $id;";

       // execute query
       $this->db->exec($updateString);
       // return updated record
       $car = $this->db->query('SELECT * from cars where id='.$id)->fetch();
       $jsonResponse = $response->withJson($car);

       return $jsonResponse;
   }); //Update cars by id
$app->post('/cars', function (Request $request, Response $response) {

     // check that peron exists
     // $athlete = $this->db->query('SELECT * from cars where id='.$id)->fetch();
     // if(!$athlete){
     //   $errorData = array('status' => 404, 'message' => 'not found');
     //   $response = $response->withJson($errorData, 404);
     //   return $response;
     // }

     // build query string
     $createString = "INSERT INTO cars ";
     $fields = $request->getParsedBody();
     $keysArray = array_keys($fields);
     $last_key = end($keysArray);
     $values = '(';
     $fieldNames = '(';
     foreach($fields as $field => $value) {
       $values = $values . "'"."$value"."'";
       $fieldNames = $fieldNames . "$field";
       if ($field != $last_key) {
         // conditionally add a comma to avoid sql syntax problems
         $values = $values . ", ";
         $fieldNames = $fieldNames . ", ";
       }
     }
     $values = $values . ')';
     $fieldNames = $fieldNames . ') VALUES ';
     $createString = $createString . $fieldNames . $values . ";";
     // execute query
     try {
       $this->db->exec($createString);
     } catch (\PDOException $e) {
       var_dump($e);
       $errorData = array('status' => 400, 'message' => 'Invalid data provided to create athlete');
       return $response->withJson($errorData, 400);
     }
     // return updated record
     $athlete = $this->db->query('SELECT * from cars ORDER BY id desc LIMIT 1')->fetch();
     $jsonResponse = $response->withJson($athlete);

     return $jsonResponse;
 });

   $app->delete('/cars/{id}', function (Request $request, Response $response, array $args) {
     $id = $args['id'];
     $this->logger->addInfo("DELETE /cars/".$id);
     $car = $this->db->exec('DELETE FROM cars where id='.$id);
     $jsonResponse = $response->withJson($car);

     return;
   }); //Delete cars by id

   $this->app = $app;
 }

 /**
  * Get an instance of the application.
  *
  * @return \api\App
  */
 public function get()
 {
     return $this->app;
 }
}
