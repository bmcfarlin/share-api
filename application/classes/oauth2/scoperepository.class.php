<?php

namespace OAuth2;

use \League\OAuth2\Server\Entities\ScopeEntityInterface;
use \League\OAuth2\Server\Entities\ClientEntityInterface;

class ScopeRepository implements \League\OAuth2\Server\Repositories\ScopeRepositoryInterface
{


  function __construct(){
  }

  function __toString(){
    return get_class($this);
  }

  /**
   * Return information about a scope.
   *
   * @param string $identifier The scope identifier
   */
  public function getScopeEntityByIdentifier(string $identifier): ?ScopeEntityInterface{
    return null;
  }

  /**
   * Given a client, grant type and optional user identifier validate the set of scopes requested are valid and optionally
   * append additional scopes or remove requested scopes.
   *
   * @param ScopeEntityInterface[] $scopes
   *
   * @return ScopeEntityInterface[]
   */
  public function finalizeScopes(array $scopes, string $grantType, ClientEntityInterface $clientEntity, string|null $userIdentifier = null, ?string $authCodeId = null): array{
    return $scopes;
  }

}


