<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Fig\Http\Message\StatusCodeInterface;

abstract class BaseController
{
  protected $registry;

  function __construct($registry)
  {
    $this->registry = $registry;

    $routeinfo = $this->registry->template->routeinfo;
    $controller = strtolower($routeinfo->controller);
    $action = strtolower($routeinfo->action);

    $meta = Meta::GetByControllerAction($controller, $action);

    if($meta)
    {
      if($meta->page_title) $this->registry->template->page_title = $meta->page_title;
      if($meta->page_description) $this->registry->template->page_description = $meta->page_description;
      if($meta->page_img) $this->registry->template->page_img = $meta->page_img;
    }else{
      if($controller != "api" && $controller != "admin")
      {
        $error = sprintf("no meta for %s.%s", $controller, $action);
        trace($error);
      }
    }
  }

  abstract function index();

  protected function noaction($action)
  {
    $s = sprintf("%s does not implement %s action", get_class($this), $action);
    print($s);
  }

  protected function redirect($url)
  {
    trace(sprintf("%s.redirect:%s", get_class($this), $url));

    if(!starts_with($url, 'http'))
    {
      if(!starts_with($url, '/en/'))
      {
        if(!starts_with($url, '/es/'))
        {
          $locale = locale();
          $value = sprintf("/%s", $locale);
          if(!starts_with($url, '/')) $value = sprintf("%s/", $value);
          $url = sprintf("%s%s", $value, $url);
        }
      }

      $https = $_SERVER['HTTPS'];
      $protocol = empty($https)? 'http' : 'https';
      $http_host = $_SERVER['HTTP_HOST'];
      $url = sprintf("%s://%s%s", $protocol, $http_host, $url);

    }
    header("location:$url");
    die;
  }

  protected function authorize()
  {
    $claims = null;

    $root = new stdClass();
    $root->status = 'success';

    $token = null;

    $http_authorization = $_SERVER['HTTP_AUTHORIZATION'];
    if($http_authorization)
    {
      if(preg_match('/^Bearer .*$/i', $http_authorization))
      {
        trace("*************** Bearer token ********************");
        $token = preg_replace('/^Bearer /i', '', $http_authorization);
      }
    }

    $__jwt = $_COOKIE["__jwt"];
    if($__jwt){
      $token = $__jwt;

      trace("*************** __jwt token ********************");

    }

    if($token){


      trace("token:$token");

      $item = Jwt::Validate($token);
      if($item->status == 'success')
      {
        foreach($item->items as $sitem)
        {
          $claims = $sitem;
        }
      }else{
        $root->status = 'error';
        $root->error = $item->error;
      }

    }else{
      $root->status = 'error';
      $root->error = 'Invalid request';
    }

    if($root->status == 'error')
    {
      header('Content-Type: application/json');
      print json_encode($root);
      die;
    }

    trace("===================== claims =============================");
    trace($claims);

    return $claims;
  }

  protected function validate(){
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
      trace("fn\n");
      return $response;
    };
    $next = $fn(...);
    $result = $middle($request, $response, $next);
  
  }

  protected function printJSON($root, $cors = 1){
    
    trace(sprintf("printJSON: %s", $this->cmd));

    if($root->items){
      $count = count($root->items);
      for($i=0;$i<$count;$i++){
        $item = $root->items[$i];
        $json = json_encode($item);
        $values = json_decode($json, true);
        ksort($values);
        $json = json_encode($values);
        $item = json_decode($json);
        $root->items[$i] = $item;
      }      

    }

    $origin = 'https://localhost';

    $http_origin = $_SERVER['HTTP_ORIGIN'];

    if($http_origin == 'null' || $http_origin == null){
      $origin = 'file://';
      $origin = '*';
    }

    $origin = '*';

    trace($root);

    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Authorization,Content-Type");
    header("Access-Control-Max-Age: 86400");
    print json_encode($root) . PHP_EOL;
  }

}
