<?php

namespace Guzzle;

use Psr\Http\Client\RequestExceptionInterface;

final class RequestException extends BasePsr18Exception implements RequestExceptionInterface
{
}