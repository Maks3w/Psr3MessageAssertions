<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\Common;

/**
 * class with useful methods for placeholders checks.
 */
class PlaceholderMethods
{
    /**
     * Check if placeholder exists in the context array.
     *
     * @return string[] List of placeholders missing in the context array.
     */
    public static function findMissingContextKeys(array $placeholders, array $contextKeys): array
    {
        $missingContextKeys = array_diff($placeholders, $contextKeys);

        $missingContextKeys = array_filter($missingContextKeys, __NAMESPACE__ . '\PlaceholderMethods::isValidPlaceholderName');

        return $missingContextKeys;
    }

    /**
     * Check if placeholder exists in the context array and contains invalid characters.
     *
     * @return string[] List of placeholders missing in the context array.
     */
    public static function findInvalidPlaceholdersNames(array $placeholders, array $contextKeys): array
    {
        $invalidPlaceholderNames = array_intersect($placeholders, $contextKeys);

        $invalidPlaceholderNames = array_filter($invalidPlaceholderNames, function ($placeholder) {
            return !self::isValidPlaceholderName($placeholder);
        });

        return $invalidPlaceholderNames;
    }

    /**
     * Extract placeholders from log message.
     */
    public static function extractPlaceholders(string $message): array
    {
        preg_match('/\{([^\}]+)\}/', $message, $matches);

        return array_slice($matches, 1);
    }

    /**
     * Validate if placeholder is composed only with valid characters.
     */
    public static function isValidPlaceholderName(string $placeholder): bool
    {
        return (bool) preg_match('/^[a-zA-Z_\.]+$/', $placeholder);
    }
}
