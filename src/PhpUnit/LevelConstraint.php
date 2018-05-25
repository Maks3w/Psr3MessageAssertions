<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit\Framework\Constraint\Constraint;
use Psr\Log\LogLevel;

/**
 * Validate log level match with defined log levels in the specification.
 *
 * Notice specification does not limit this aspect and custom log level is allowed.
 *
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#11-basics
 */
class LevelConstraint extends Constraint
{
    /**
     * @var string[]
     */
    protected $allowedLevels = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG,
    ];

    protected function matches($other): bool
    {
        return in_array($other, $this->allowedLevels, true);
    }

    public function toString(): string
    {
        return 'is a recognized log level (' . implode(', ', $this->allowedLevels) . ')';
    }
}
