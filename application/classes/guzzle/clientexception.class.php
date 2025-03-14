<?php

namespace Guzzle;

use Psr\Http\Client\ClientExceptionInterface;

final class ClientException extends BasePsr18Exception implements ClientExceptionInterface
{
}