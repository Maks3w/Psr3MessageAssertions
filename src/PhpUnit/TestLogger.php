<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit\Framework\Assert;
use Psr\Log\AbstractLogger;

/**
 * Provide a Logger implementation which inspect the log message for PSR-3 specification compliant.
 */
class TestLogger extends AbstractLogger
{
    public function log($level, $message, array $context = [])
    {
        $this->assertLogLevelIsValid($level);
        $this->assertMessageType($message);
        $this->assertContext($context);
        $this->assertPlaceholder($message, $context);
    }

    /**
     * Asserts log level match with defined log levels in the specification.
     */
    public function assertLogLevelIsValid(string $level, string $message = '')
    {
        $constraint = new LevelConstraint();

        Assert::assertThat($level, $constraint, $message);
    }

    /**
     * Asserts message is a valid argument type.
     */
    public function assertMessageType($logMessage, string $message = '')
    {
        $constraint = new MessageTypeConstraint();

        Assert::assertThat($logMessage, $constraint, $message);
    }

    /**
     * Asserts log message context is well formed.
     */
    public function assertContext(array $context, string $message = '')
    {
        $constraint = new ExceptionsInContextConstraint();

        Assert::assertThat($context, $constraint, $message);
    }

    /**
     * Asserts log message placeholders are well formed.
     */
    public function assertPlaceholder(string $logMessage, array $context, string $message = '')
    {
        $constraint = new MissingPlaceholderInContextConstraint($context);

        Assert::assertThat($logMessage, $constraint, $message);

        $constraint = new ValidPlaceholderNameInContextConstraint($context);

        Assert::assertThat($logMessage, $constraint, $message);
    }
}
