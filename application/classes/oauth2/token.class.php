<?php

namespace OAuth2;

use \League\OAuth2\Server\Entities\ClientEntityInterface;
use \League\OAuth2\Server\Entities\ScopeEntityInterface;

class Token implements \League\OAuth2\Server\Entities\TokenInterface
{
  private $identifier;
  private $dateTime;
  private $user_identifier;
  private $client;
  private $scopes;

  function __construct(){
  }

  function __toString(){
    return get_class($this);
  }

  /**
   * Get the token's identifier.
   *
   * @return non-empty-string
   */
  public function getIdentifier(): string
  {
    return $this->identifier;
  }
  /**
   * Set the token's identifier.
   *
   * @param non-empty-string $identifier
   */
  public function setIdentifier(string $identifier): void
  {
    $this->identifier = $identifier;
  }
  /**
   * Get the token's expiry date time.
   */
  public function getExpiryDateTime(): \DateTimeImmutable
  {
    return $this->dateTime;
  }

  /**
   * Set the date time when the token expires.
   */
  public function setExpiryDateTime(\DateTimeImmutable $dateTime): void
  {
    $this->dateTime = $dateTime;
  }
  /**
   * Set the identifier of the user associated with the token.
   *
   * @param non-empty-string $identifier
   */
  public function setUserIdentifier(string $identifier): void
  {
    $this->user_identifier = $identifier;
  }
  /**
   * Get the token user's identifier.
   *
   * @return non-empty-string|null
   */
  public function getUserIdentifier(): string|null
  {
    return $this->user_identifier;
  }

  /**
   * Get the client that the token was issued to.
   */
  public function getClient(): ClientEntityInterface
  {
    return $this->client;
  }

  /**
   * Set the client that the token was issued to.
   */
  public function setClient(ClientEntityInterface $client): void
  {
    $this->client = $client;
  }
  /**
   * Associate a scope with the token.
   */
  public function addScope(ScopeEntityInterface $scope): void
  {
    array_push($this->scopes, $scope);
  }
  /**
   * Return an array of scopes associated with the token.
   *
   * @return ScopeEntityInterface[]
   */
  public function getScopes(): array
  {
    if(empty($this->scopes)){
      $this->scopes = [];
    }
    return $this->scopes;
  }

}


