<?php

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Validate message is a valid argument type.
 *
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#13-context
 */
class ExceptionsInContextConstraint extends Constraint
{
    protected function matches($other)
    {
        return (null === $this->findExceptions($other));
    }

    protected function failureDescription($other)
    {
        return $this->toString() . '. The key "' . $this->findExceptions($other) . '" contains an exception object';
    }

    public function toString()
    {
        return 'exceptions are located in the "exception" key';
    }

    /**
     * Inspect log message context for exceptions in context keys distinct of "exception".
     *
     * @return null|string `null` if there is no exception in a wrong context key or `string` with the erroneous context key.
     */
    protected function findExceptions(array $context)
    {
        foreach ($context as $key => $val) {
            if ($val instanceof \Exception && ($key !== 'exception')) {
                return $key;
            }
        }

        return null;
    }
}
