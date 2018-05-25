<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestFailure;
use Psr\Log\LogLevel;

class LevelConstraintTest extends TestCase
{
    /**
     * @var Constraint
     */
    protected $constraint;

    protected function setUp()
    {
        $this->constraint = new LevelConstraint();
    }

    public function testConstraintDefinition()
    {
        self::assertEquals(1, count($this->constraint));
        self::assertEquals(
            'is a recognized log level (emergency, alert, critical, error, warning, notice, info, debug)',
            $this->constraint->toString()
        );
    }

    /**
     * @dataProvider validLogLevelProvider
     *
     * @param string $logLevel
     *
     * @return void
     */
    public function testValidLogLevel($logLevel)
    {
        self::assertTrue($this->constraint->evaluate($logLevel, '', true));
    }

    /**
     * @dataProvider invalidLogLevelProvider
     *
     * @param $logLevel
     *
     * @return void
     */
    public function testInvalidLogLevel($logLevel)
    {
        self::assertFalse($this->constraint->evaluate($logLevel, '', true));

        try {
            $this->constraint->evaluate($logLevel);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertEquals(
                <<<EOF
Failed asserting that '$logLevel' is a recognized log level (emergency, alert, critical, error, warning, notice, info, debug).

EOF
                ,
                TestFailure::exceptionToString($e)
            );
        }
    }

    public function validLogLevelProvider()
    {
        return [
            LogLevel::EMERGENCY => ['emergency'],
            LogLevel::ALERT => ['alert'],
            LogLevel::CRITICAL => ['critical'],
            LogLevel::ERROR => ['error'],
            LogLevel::WARNING => ['warning'],
            LogLevel::NOTICE => ['notice'],
            LogLevel::INFO => ['info'],
            LogLevel::DEBUG => ['debug'],
        ];
    }

    public function invalidLogLevelProvider()
    {
        return [
            'emerg' => ['emerg'],
            'crit' => ['crit'],
            'err' => ['err'],
        ];
    }
}
