<?php

namespace OAuth2;

use \League\OAuth2\Server\CryptKeyInterface;

class AccessTokenEntity extends \OAuth2\Token implements \League\OAuth2\Server\Entities\AccessTokenEntityInterface
{

  use \League\OAuth2\Server\Entities\Traits\AccessTokenTrait; 

  private CryptKeyInterface $privateKey;

  function __construct(){
  }

  function __toString(){
    return get_class($this);
  }

  /**
   * Set a private key used to encrypt the access token.
   */
  public function setPrivateKey(CryptKeyInterface $privateKey): void
  {
    $this->privateKey = $privateKey;
  }

  /**
   * Generate a string representation of the access token.
   */
  public function toString(): string
  {
    return $this->convertToJWT()->toString();
  }

}
