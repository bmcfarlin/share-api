<?php

namespace OAuth2;

class CryptKey implements \League\OAuth2\Server\CryptKeyInterface
{

  function __construct(){
  }

  function __toString(){
    return get_class($this);
  }

  /**
   * Retrieve key path.
   */
  public function getKeyPath(): string
  {
    return "/var/www/sh.qea.la/application/docs/private.key";
  }

  /**
   * Retrieve key pass phrase.
   */
  public function getPassPhrase(): ?string
  {
    return '';
  }

  /**
   * Get key contents
   *
   * @return string Key contents
   */
  public function getKeyContents(): string
  {
    $result = '';
    $key_path = $this->getKeyPath();
    if(file_exists($key_path)){
      $result = file_get_contents($key_path);
    }
    return $result;
  }
}
