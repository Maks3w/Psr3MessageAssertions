<?php

namespace FR3D\Psr3MessagesAssertionsTest\PhpUnit;

use FR3D\Psr3MessagesAssertions\PhpUnit\LevelConstraint;
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
        $this->assertEquals(1, count($this->constraint));
        $this->assertEquals(
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
        $this->assertTrue($this->constraint->evaluate($logLevel, '', true));
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
        $this->assertFalse($this->constraint->evaluate($logLevel, '', true));

        try {
            $this->constraint->evaluate($logLevel);
            $this->fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
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
        return array(
            LogLevel::EMERGENCY => array('emergency'),
            LogLevel::ALERT => array('alert'),
            LogLevel::CRITICAL => array('critical'),
            LogLevel::ERROR => array('error'),
            LogLevel::WARNING => array('warning'),
            LogLevel::NOTICE => array('notice'),
            LogLevel::INFO => array('info'),
            LogLevel::DEBUG => array('debug'),
        );
    }

    public function invalidLogLevelProvider()
    {
        return array(
            'emerg' => array('emerg'),
            'crit' => array('crit'),
            'err' => array('err'),
        );
    }
}
