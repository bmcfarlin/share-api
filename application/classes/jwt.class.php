<?php

  use Jose\Component\Core\AlgorithmManager;
  use Jose\Component\Encryption\Algorithm\KeyEncryption\RSAOAEP256;
  use Jose\Component\Encryption\Algorithm\ContentEncryption\A256GCM;
  use Jose\Component\Encryption\Compression\CompressionMethodManager;
  use Jose\Component\Encryption\Compression\Deflate;
  use Jose\Component\Encryption\JWEBuilder;
  use Jose\Component\Core\JWK;
  use Jose\Component\Encryption\Serializer\CompactSerializer;
  use Jose\Component\Encryption\Serializer\JSONFlattenedSerializer;
  use Jose\Component\Encryption\JWEDecrypter;
  use Jose\Component\Encryption\Serializer\JWESerializerManager;
  use Jose\Component\Checker\HeaderCheckerManager;
  use Jose\Component\Checker\AlgorithmChecker;
  use Jose\Component\Encryption\JWETokenSupport;
  use Jose\Component\Checker\ClaimCheckerManager;
  use Jose\Component\Checker\IssuerChecker;
  use Jose\Component\Checker\ExpirationTimeChecker;

class Jwt
{

  function __toString(){
    return get_class($this);
  }

  static function GetNew($private_claims = null){

    if($private_claims){
      if(!is_array($private_claims)){
        if(!is_assoc($private_claims)){
          die('private claims must be an associative array');
        }
      }
    }

    $value = null;

    $key_manager = new AlgorithmManager([new RSAOAEP256()]);
    $content_manager = new AlgorithmManager([new A256GCM()]);
    $compression_manager = new CompressionMethodManager([new Deflate()]);
    $jwe_builder = new JWEBuilder($key_manager, $content_manager, $compression_manager);

    $jwk = new JWK(JWT_KEY);

    $jwp = $jwk->toPublic();

    $http_user_agent = $_SERVER['HTTP_USER_AGENT'];

    $claims = [
      'iss' => 'gasvalet.me',
      'exp' => time() + JWT_COOKIE_DURATION,
      'agt' => $http_user_agent
    ];

    // trace("claims");
    // trace($claims);
    // trace("private_claims");
    // trace($private_claims);

    if($private_claims){
      $claims = array_merge($claims, $private_claims);
    }

    //trace("total_claims");
    //trace($claims);

    $payload = json_encode($claims);

    $jwe = $jwe_builder
      ->create()
      ->withPayload($payload)
      ->withSharedProtectedHeader([
        'alg' => 'RSA-OAEP-256',
        'enc' => 'A256GCM'
      ])
      ->addRecipient($jwp)
      ->build();

    $compact_serializer = new CompactSerializer();
    $token = $compact_serializer->serialize($jwe, 0);

    $value = $token;

    return $value;
  }

  static function Validate($token){

    $root = new stdClass();
    $root->status = 'success';

    $http_user_agent = $_SERVER['HTTP_USER_AGENT'];

    try{
      $compact_serializer = new CompactSerializer();
      $jwe = $compact_serializer->unserialize($token);

      $header_checker_manager = new HeaderCheckerManager([new AlgorithmChecker(['RSA-OAEP-256'])],[new JWETokenSupport()]);
      $header_checker_manager->check($jwe, 0, ['alg', 'enc']);

      $key_manager = new AlgorithmManager([new RSAOAEP256()]);
      $content_manager = new AlgorithmManager([new A256GCM()]);
      $compression_manager = new CompressionMethodManager([new Deflate()]);

      $jwk = new JWK(JWT_KEY);

      $jwe_decrypter = new JWEDecrypter($key_manager, $content_manager, $compression_manager);
      $success = $jwe_decrypter->decryptUsingKey($jwe, $jwk, 0);

      $json = $jwe->getPayload();
      $claims = json_decode($json, true);

      $claim_checker_manager = new ClaimCheckerManager([new IssuerChecker(['gasvalet.me']),new ExpirationTimeChecker(), new UserAgentChecker([$http_user_agent])]);
      $claim_checker_manager->check($claims, ['iss', 'exp', 'agt']);

      $item = json_decode($json);
      $root->items = [$item];

    }catch(Exception $exc){
      $root->status = 'error';
      $root->error = $exc->getMessage();
    }

    return $root;
  }

}


