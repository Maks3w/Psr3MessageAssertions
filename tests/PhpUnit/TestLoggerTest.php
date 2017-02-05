<?php

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit_Framework_ExpectationFailedException as ExpectationFailedException;
use PHPUnit_Framework_TestCase as TestCase;

class TestLoggerTest extends TestCase
{
    /**
     * @var TestLogger
     */
    protected $logger;

    protected function setUp()
    {
        $this->logger = new TestLogger();
    }

    public function testValidLogLevel()
    {
        $this->logger->assertLogLevelIsValid('debug');
    }

    public function testInvalidLogLevel()
    {
        try {
            $this->logger->assertLogLevelIsValid('invalid');
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertTrue(true);
        }
    }

    public function testValidMessageType()
    {
        $this->logger->assertMessageType('');
    }

    public function testInvalidMessageType()
    {
        try {
            $this->logger->assertMessageType(new \stdClass());
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertTrue(true);
        }
    }

    public function testValidContext()
    {
        $this->logger->assertContext(['foo' => 'baz']);
    }

    public function testInvalidContext()
    {
        try {
            $this->logger->assertContext(['foo' => new \Exception()]);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertTrue(true);
        }
    }

    public function testValidPlaceholder()
    {
        $this->logger->assertPlaceholder('{foo}', ['foo' => 'baz']);
        $this->logger->assertPlaceholder('{$foo}', ['foo' => 'baz']);
    }

    public function testPlaceholderIsMissing()
    {
        try {
            $this->logger->assertPlaceholder('{foo}', []);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertTrue(true);
        }
    }

    public function testPlaceholderNameIsInvalid()
    {
        try {
            $this->logger->assertPlaceholder('{$foo}', ['$foo' => 'baz']);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertTrue(true);
        }
    }

    public function testValidLog()
    {
        $this->logger->log(
            'debug',
            '{$foo} {baz_}{bAz}',
            [
                'baz_' => 'value',
                'bAz' => [],
                'exception' => new \Exception(),
                'extra' => new \stdClass(),
            ]
        );

        self::assertTrue(true);
    }

    /**
     * @dataProvider invalidLogProvider
     *
     * @param string $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function testInvalidLogMessage($level, $message, array $context)
    {
        try {
            $this->logger->log($level, $message, $context);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertTrue(true);
        }
    }

    public function invalidLogProvider()
    {
        return [
            'invalid log level' => ['invalid_level', '', []],
            'invalid log type' => ['debug', [], []],
            'missing placeholder in context' => ['debug', '{foo}', []],
            'invalid placeholder name' => ['debug', '{$foo}', ['$foo' => '']],
            'invalid exception context key' => ['debug', '', ['foo' => new \Exception()]],
        ];
    }
}
