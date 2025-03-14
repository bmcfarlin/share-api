<?php

namespace Guzzle;

use Exception;
use Psr\Http\Message\RequestInterface;
use Throwable;

abstract class BasePsr18Exception extends Exception
{
    protected RequestInterface $request;

    final private function __construct(string $message, int $code, ?Throwable $previous)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromRequest(
        RequestInterface $request,
        string $message,
        int $code,
        ?Throwable $previous
    ): self {
        $exception          = new static($message, $code, $previous);
        $exception->request = $request;

        return $exception;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}


