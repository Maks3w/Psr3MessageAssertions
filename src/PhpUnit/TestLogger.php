<?php

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit_Framework_Assert as Assert;
use Psr\Log\AbstractLogger;

/**
 * Provide a Logger implementation which inspect the log message for PSR-3 specification compliant.
 */
class TestLogger extends AbstractLogger
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        $this->assertLogLevelIsValid($level);
        $this->assertMessageType($message);
        $this->assertContext($context);
        $this->assertPlaceholder($message, $context);
    }

    /**
     * Asserts log level match with defined log levels in the specification.
     *
     * @param string $level
     * @param string $message
     */
    public function assertLogLevelIsValid($level, $message = '')
    {
        $constraint = new LevelConstraint();

        Assert::assertThat($level, $constraint, $message);
    }

    /**
     * Asserts message is a valid argument type.
     *
     * @param string $logMessage
     * @param string $message
     */
    public function assertMessageType($logMessage, $message = '')
    {
        $constraint = new MessageTypeConstraint();

        Assert::assertThat($logMessage, $constraint, $message);
    }

    /**
     * Asserts log message context is well formed.
     *
     * @param array $context
     * @param string $message
     */
    public function assertContext(array $context, $message = '')
    {
        $constraint = new ExceptionsInContextConstraint();

        Assert::assertThat($context, $constraint, $message);
    }


    /**
     * Asserts log message placeholders are well formed.
     *
     * @param string $logMessage
     * @param array $context
     * @param string $message
     */
    public function assertPlaceholder($logMessage, array $context, $message = '')
    {
        $constraint = new MissingPlaceholderInContextConstraint($context);

        Assert::assertThat($logMessage, $constraint, $message);

        $constraint = new ValidPlaceholderNameInContextConstraint($context);

        Assert::assertThat($logMessage, $constraint, $message);
    }
}
