<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Fig\Http\Message\StatusCodeInterface;

class ApiController extends BaseController
{
  public function index()
  {
    print 'Api';
  }

  public function test()
  {
    $this->validate();
    $value = new stdClass();
    $value->status = 'success';
    header('Content-Type: application/json');
    print json_encode($value);
  }

  private function validate(){
    trace("validate");

    $accessTokenRepository = new \OAuth2\AccessTokenRepository();
    $publicKey = __DIR__ . '/../docs/public.key';
    $server = new \League\OAuth2\Server\ResourceServer(
      $accessTokenRepository,
      $publicKey
    );

    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];
    $factory = new \Slim\Psr7\Factory\UriFactory();
    $uri = $factory->createUri($uri);
    $headers = getallheaders();
    $headers = new \Slim\Psr7\Headers($headers);

    $stream = fopen('php://memory', 'r+');
    fwrite($stream, file_get_contents("php://input"));
    rewind($stream);

    $body = new \Slim\Psr7\Stream($stream);
    $request = new \Slim\Psr7\Request($method, $uri, $headers, $_COOKIE, $_SERVER, $body);
    $request = $request->withParsedBody($_POST);

    $stream2 = fopen('php://memory', 'r+');
    $body2 = new \Slim\Psr7\Stream($stream2);
    $response = new \Slim\Psr7\Response(StatusCodeInterface::STATUS_OK, null, $body2);

    $middle = new \League\OAuth2\Server\Middleware\ResourceServerMiddleware($server);
    $fn = function($request, $response) {
      print("fn\n");
      return $response;
    };
    $next = $fn(...);
    $result = $middle($request, $response, $next);
  
  }


}