<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use FR3D\Psr3MessagesAssertions\Common\PlaceholderMethods;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * Validate message placeholders name use allowed characters subset.
 *
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#12-message
 */
class ValidPlaceholderNameInContextConstraint extends Constraint
{
    /**
     * Message context.
     *
     * @var array
     */
    protected $contextKeys;

    public function __construct(array $context)
    {
        $this->contextKeys = array_keys($context);
    }

    protected function matches($other): bool
    {
        $placeholders = PlaceholderMethods::extractPlaceholders($other);

        $missingContextKeys = PlaceholderMethods::findInvalidPlaceholdersNames($placeholders, $this->contextKeys);

        return empty($missingContextKeys);
    }

    protected function failureDescription($other): string
    {
        $placeholders = PlaceholderMethods::extractPlaceholders($other);

        $missingContextKeys = PlaceholderMethods::findInvalidPlaceholdersNames($placeholders, $this->contextKeys);

        $isSingle = (count($missingContextKeys) === 1);

        return sprintf(
            'the %s name "%s" %s composed composed only of the characters A-Z, a-z, 0-9, period . and underscore _',
            $isSingle ? 'placeholder' : 'placeholders',
            implode(', ', $missingContextKeys),
            $isSingle ? 'is' : 'are'
        );
    }

    public function toString(): string
    {
        return 'placeholder has valid name';
    }
}
