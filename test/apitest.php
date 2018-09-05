<?php
use PHPUnit\Framework\TestCase;
use api\Http\Environment;
use api\Http\Request;
require './vendor/autoload.php';

class mockDB {
  public function query(){}
}
class mockQuery {
  public function fetchAll(){}
}
class carsTest extends TestCase
{
  protected $app;
  protected $db;


  public function setUp()
  {
    $this->db = $this->createMock('mockDb');
    $this->app = (new Davian\api\App($this->db))->get();
  }

  public function testHelloName() {
    $env = Environment::mock([
        'REQUEST_METHOD' => 'GET',
        'REQUEST_URI'    => '/hello/Joe',
        ]);
    $req = Request::createFromEnvironment($env);
    $this->app->getContainer()['request'] = $req;
    $response = $this->app->run(true);
    $this->assertSame($response->getStatusCode(), 200);
    $this->assertSame((string)$response->getBody(), "Nice Car");
  }
  // test the GET cars endpoint
public function testGetcars() {
  // expected result string
  $resultString = '[{"id":"1","name":"Toyota Camry","year":"2018","country":"Japan"},{"id":"2","name":"Honda Civic","year":"2007","country":"Japan"},{"id":"3","name":"BMW","year":"2001","country":"Germany"},{"id":"4","name":"Audi","year":"2012","country":"Germany"},{"id":"5","name":"Dodge Challenger","year":"2017","country":"U.S"},{"id":"6","name":"Ford","year":"2009","country":"U.S"}, {"id":"7","name":"Mercedes Benz C Class","year":"2013","country":"Germany"}'
  // mock the query class & fetchAll functions
  $query = $this->createMock('mockQuery');
  $query->method('fetchAll')
    ->willReturn(json_decode($resultString, true)
  );
   $this->db->method('query')
         ->willReturn($query);
  // mock the request environment.  (part of api)
  $env = Environment::mock([
      'REQUEST_METHOD' => 'GET',
      'REQUEST_URI'    => '/cars',
      ]);
  $req = Request::createFromEnvironment($env);
  $this->app->getContainer()['request'] = $req;
  // actually run the request through the app.
  $response = $this->app->run(true);
  // assert expected status code and body
  $this->assertSame($response->getStatusCode(), 200);
  $this->assertSame((string)$response->getBody(), $resultString);
}
}
