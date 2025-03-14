<?php

namespace Guzzle;

use Psr\Http\Client\NetworkExceptionInterface;

final class NetworkException extends BasePsr18Exception implements NetworkExceptionInterface
{
}