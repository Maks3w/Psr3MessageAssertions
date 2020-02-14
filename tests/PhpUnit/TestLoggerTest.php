<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class TestLoggerTest extends TestCase
{
    /**
     * @var TestLogger
     */
    protected $logger;

    protected function setUp(): void
    {
        $this->logger = new TestLogger();
    }

    public function testValidLogLevel(): void
    {
        $this->logger->assertLogLevelIsValid('debug');
    }

    public function testInvalidLogLevel(): void
    {
        try {
            $this->logger->assertLogLevelIsValid('invalid');
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertTrue(true);
        }
    }

    public function testValidMessageType(): void
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

    public function testValidContext(): void
    {
        $this->logger->assertContext(['foo' => 'baz']);
    }

    public function testInvalidContext(): void
    {
        try {
            $this->logger->assertContext(['foo' => new \Exception()]);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertTrue(true);
        }
    }

    public function testValidPlaceholder(): void
    {
        $this->logger->assertPlaceholder('{foo}', ['foo' => 'baz']);
        $this->logger->assertPlaceholder('{$foo}', ['foo' => 'baz']);
    }

    public function testPlaceholderIsMissing(): void
    {
        try {
            $this->logger->assertPlaceholder('{foo}', []);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertTrue(true);
        }
    }

    public function testPlaceholderNameIsInvalid(): void
    {
        try {
            $this->logger->assertPlaceholder('{$foo}', ['$foo' => 'baz']);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertTrue(true);
        }
    }

    public function testValidLog(): void
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
    public function testInvalidLogMessage($level, $message, array $context): void
    {
        try {
            $this->logger->log($level, $message, $context);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertTrue(true);
        }
    }

    public function invalidLogProvider(): array
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
