<?php

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit_Framework_ExpectationFailedException as ExpectationFailedException;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_TestFailure as TestFailure;
use Psr\Log\LogLevel;

class LevelConstraintTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_Constraint
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
