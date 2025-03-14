<?php

namespace OAuth2;

use \League\OAuth2\Server\Entities\ClientEntityInterface;

class ClientEntity implements \League\OAuth2\Server\Entities\ClientEntityInterface
{

  private $id;
  private $name;
  private $url;
  private $conf;

  function __construct(string $id, string $name, string $url, bool $conf){
    $this->id = $id;
    $this->name = $name;
    $this->url = $url;
    $this->conf = $conf;
  }

  function __toString(){
    return get_class($this);
  }


  /**
   * Get the client's identifier.
   *
   * @return non-empty-string
   */
  public function getIdentifier(): string
  {
    return $this->id;
  }

  /**
   * Get the client's name.
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Returns the registered redirect URI (as a string). Alternatively return
   * an indexed array of redirect URIs.
   *
   * @return string|string[]
   */
  public function getRedirectUri(): string|array
  {
    return $this->url;
  }

  /**
   * Returns true if the client is confidential.
   */
  public function isConfidential(): bool
  {
    return $this->conf;
  }

  /*
   * Returns true if the client supports the given grant type.
   *
   * TODO: To be added in a future major release.
   */
  // public function supportsGrantType(string $grantType): bool;
}
