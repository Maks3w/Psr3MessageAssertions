<?php

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit_Framework_Constraint as Constraint;

/**
 * Validate message is a valid argument type.
 *
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#12-message
 */
class MessageTypeConstraint extends Constraint
{
    protected function matches($other)
    {
        $canCastToString = true;

        set_error_handler(function () use (&$canCastToString) {
            $canCastToString = false;

            return;
        });

        $other = (string) $other;

        restore_error_handler();

        return $canCastToString;
    }

    protected function failureDescription($other)
    {
        return gettype($other) . ' ' . $this->toString();
    }

    public function toString()
    {
        return 'is string or a object with a __toString() method';
    }
}
