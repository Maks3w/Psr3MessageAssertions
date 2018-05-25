<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Validate message is a valid argument type.
 *
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#12-message
 */
class MessageTypeConstraint extends Constraint
{
    protected function matches($other): bool
    {
        if (is_array($other)) {
            return false;
        }

        $canCastToString = true;

        set_error_handler(function () use (&$canCastToString) {
            $canCastToString = false;
        });

        $other = (string) $other;

        restore_error_handler();

        return $canCastToString;
    }

    protected function failureDescription($other): string
    {
        return gettype($other) . ' ' . $this->toString();
    }

    public function toString(): string
    {
        return 'is string or a object with a __toString() method';
    }
}
