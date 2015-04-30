<?php

namespace FR3D\Psr3MessagesAssertions\Common;

/**
 * class with useful methods for placeholders checks.
 */
class PlaceholderMethods
{
    /**
     * Check if placeholder exists in the context array.
     *
     * @param array $placeholders
     * @param array $contextKeys
     *
     * @return string[] List of placeholders missing in the context array.
     */
    public static function findMissingContextKeys(array $placeholders, array $contextKeys)
    {
        $missingContextKeys = array_diff($placeholders, $contextKeys);

        $missingContextKeys = array_filter($missingContextKeys, __NAMESPACE__ . '\PlaceholderMethods::isValidPlaceholderName');

        return $missingContextKeys;
    }

    /**
     * Check if placeholder exists in the context array and contains invalid characters.
     *
     * @param array $placeholders
     * @param array $contextKeys
     *
     * @return string[] List of placeholders missing in the context array.
     */
    public static function findInvalidPlaceholdersNames(array $placeholders, array $contextKeys)
    {
        $invalidPlaceholderNames = array_intersect($placeholders, $contextKeys);

        $invalidPlaceholderNames = array_filter($invalidPlaceholderNames, function($placeholder) {
            return !self::isValidPlaceholderName($placeholder);
        });

        return $invalidPlaceholderNames;
    }

    /**
     * Extract placeholders from log message.
     *
     * @param string $message Log message.
     *
     * @return array Placeholders.
     */
    public static function extractPlaceholders($message)
    {
        preg_match('/\{([^\}]+)\}/', $message, $matches);

        return array_slice($matches, 1);
    }

    /**
     * Validate if placeholder is composed only with valid characters.
     *
     * @param string $placeholder
     *
     * @return bool
     */
    public static function isValidPlaceholderName($placeholder)
    {
        return (bool)preg_match('/^[a-zA-Z_\.]+$/', $placeholder);
    }
}
