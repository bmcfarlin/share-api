<?php

namespace OAuth2;

use \League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use \League\OAuth2\Server\Entities\ClientEntityInterface;
use \League\OAuth2\Server\CryptKeyInterface;

class AccessTokenRepository implements \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
{

  function __construct(){
  }

  function __toString(){
    return get_class($this);
  }

    /**
     * Create a new access token
     *
     * @param ScopeEntityInterface[] $scopes
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, string|null $userIdentifier = null): AccessTokenEntityInterface{
      trace("getNewToken");
      $entity = new \OAuth2\AccessTokenEntity();
      $entity->setClient($clientEntity);
      // todo: handle scopes
      if($userIdentifier){
        $entity->setUserIdentifier($userIdentifier);
      }
      return $entity;
    }

    /**
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
      trace("persistNewAccessToken");
      trace($accessTokenEntity->getIdentifier());
    }

    public function revokeAccessToken(string $tokenId): void{
      trace("revokeAccessToken");
      trace($tokenId);

    }

    public function isAccessTokenRevoked(string $tokenId): bool{
      trace("isAccessTokenRevoked");
      trace($tokenId);
      return false;
    }

}


