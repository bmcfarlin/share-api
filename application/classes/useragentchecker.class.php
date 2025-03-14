<?php

declare(strict_types=1);

use Jose\Component\Checker\ClaimChecker;
use Jose\Component\Checker\HeaderChecker;
use Jose\Component\Checker\InvalidClaimException;

final class UserAgentChecker implements ClaimChecker, HeaderChecker
{
    private const CLAIM_NAME = 'agt';

    /**
     * @var bool
     */
    private $protectedHeader = false;

    /**
     * @var array
     */
    private $userAgents;

    public function __construct(array $userAgents, bool $protectedHeader = false)
    {
        $this->userAgents = $userAgents;
        $this->protectedHeader = $protectedHeader;
    }

    public function checkClaim($value): void
    {
        $this->checkValue($value, InvalidClaimException::class);
    }

    public function checkHeader($value): void
    {
        $this->checkValue($value, InvalidHeaderException::class);
    }

    public function supportedClaim(): string
    {
        return self::CLAIM_NAME;
    }

    public function supportedHeader(): string
    {
        return self::CLAIM_NAME;
    }

    public function protectedHeaderOnly(): bool
    {
        return $this->protectedHeader;
    }

    /**
     * @param mixed $value
     */
    private function checkValue($value, string $class): void
    {
        if (!\is_string($value)) {
            throw new $class('Invalid value.', self::CLAIM_NAME, $value);
        }
        if (!\in_array($value, $this->userAgents, true)) {
            throw new $class('Unknown user agent.', self::CLAIM_NAME, $value);
        }
    }
}