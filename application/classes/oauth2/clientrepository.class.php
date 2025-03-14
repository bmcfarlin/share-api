<?php

namespace OAuth2;

use \League\OAuth2\Server\Entities\ClientEntityInterface;

class ClientRepository implements \League\OAuth2\Server\Repositories\ClientRepositoryInterface
{
  function __construct(){
  }

  function __toString(){
    return get_class($this);
  }

  /**
   * Get a client.
   */
  public function getClientEntity(string $clientIdentifier): ?ClientEntityInterface{
    trace("getClientEntity");
    trace($clientIdentifier);
    $id = $clientIdentifier;
    $name = $clientIdentifier;
    $url = '';
    $conf = true;
    $value = new \OAuth2\ClientEntity($id, $name, $url, $conf);
    return $value;
  }

  /**
   * Validate a client's secret.
   */
  public function validateClient(string $clientIdentifier, ?string $clientSecret, ?string $grantType): bool{
    trace("validateClient");
    trace($clientIdentifier);
    $result = false;
    if($clientIdentifier == 'YEay1uoC0k3d1Knjb4q3zVqTUI2SyFCW'){
      if($clientSecret == 'wrE2avHnL5zreDnk'){
        if($grantType == 'client_credentials'){
          $result = true;
        }
      }
    }
    return $result;
  }

}


