<?php

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use FR3D\Psr3MessagesAssertions\Common\PlaceholderMethods;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * Validate log message placeholders are present in the context array keys.
 *
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#12-message
 */
class MissingPlaceholderInContextConstraint extends Constraint
{
    /**
     * Message context.
     *
     * @var array
     */
    protected $contextKeys;

    /**
     * @param array $context
     */
    public function __construct(array $context)
    {
        parent::__construct();

        $this->contextKeys = array_keys($context);
    }

    protected function matches($other)
    {
        $placeholders = PlaceholderMethods::extractPlaceholders($other);

        $missingContextKeys = PlaceholderMethods::findMissingContextKeys($placeholders, $this->contextKeys);

        return empty($missingContextKeys);
    }

    protected function failureDescription($other)
    {
        $placeholders = PlaceholderMethods::extractPlaceholders($other);

        $missingContextKeys = PlaceholderMethods::findMissingContextKeys($placeholders, $this->contextKeys);

        $isSingle = (count($missingContextKeys) === 1);

        return sprintf(
            'the %s "%s" exists in the context array',
            $isSingle ? 'placeholder' : 'placeholders',
            implode(', ', $missingContextKeys)
        );
    }

    public function toString()
    {
        return 'placeholder exists in the context array';
    }
}
