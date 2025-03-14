<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Fig\Http\Message\StatusCodeInterface;


class OauthController extends BaseController
{
  public function index()
  {
    print 'Oauth';
  }

  public function access_token()
  {

    $clientRepository = new \OAuth2\ClientRepository();
    $accessTokenRepository = new \OAuth2\AccessTokenRepository();
    $scopeRepository = new \OAuth2\ScopeRepository();

    $privateKey = __DIR__ . '/../docs/private.key';
    $encryptionKey = \Defuse\Crypto\Key::loadFromAsciiSafeString(OAUTH_KEY);

    $server = new \League\OAuth2\Server\AuthorizationServer(
      $clientRepository,
      $accessTokenRepository,
      $scopeRepository,
      $privateKey,
      $encryptionKey
    );

    $server->enableGrantType(
      new \League\OAuth2\Server\Grant\ClientCredentialsGrant(),
        new \DateInterval('PT1H') // access tokens will expire after 1 hour
      );

    try {

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

      $result = $server->respondToAccessTokenRequest($request, $response);
      
    } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
      
      $result = $exception->generateHttpResponse($response);
      
    } catch (\Exception $exception) {
      
      $body = new \Slim\Psr7\Stream('php://temp', 'r+');
      $body->write($exception->getMessage());
      $result = $response->withStatus(500)->withBody($body);
      
    }

    $headers = $result->getHeaders();
    foreach($headers as $key => $values){
      foreach($values as $value){
        header("$key: $value");
      }
    }

    $body = $result->getBody();
    print("$body\n");
  }

}