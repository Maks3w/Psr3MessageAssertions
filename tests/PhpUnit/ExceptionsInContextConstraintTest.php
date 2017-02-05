<?php

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit_Framework_ExpectationFailedException as ExpectationFailedException;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_TestFailure as TestFailure;

class ExceptionsInContextConstraintTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_Constraint
     */
    protected $constraint;

    protected function setUp()
    {
        $this->constraint = new ExceptionsInContextConstraint();
    }

    public function testConstraintDefinition()
    {
        self::assertEquals(1, count($this->constraint));
        self::assertEquals(
            'exceptions are located in the "exception" key',
            $this->constraint->toString()
        );
    }

    /**
     * @dataProvider validContextProvider
     *
     * @param string|object $context
     *
     * @return void
     */
    public function testValidConstraint($context)
    {
        self::assertTrue($this->constraint->evaluate($context, '', true));
    }

    /**
     * @dataProvider invalidContextProvider
     *
     * @param mixed $message
     * @param string $invalidKey context key with the invalid object.
     *
     * @return void
     */
    public function testInvalidConstraint($message, $invalidKey)
    {
        self::assertFalse($this->constraint->evaluate($message, '', true));

        try {
            $this->constraint->evaluate($message);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertEquals(
                <<<EOF
Failed asserting that exceptions are located in the "exception" key. The key "$invalidKey" contains an exception object.

EOF
                ,
                TestFailure::exceptionToString($e)
            );
        }
    }

    public function validContextProvider()
    {
        return array(
            'exception key with Exception' => array(array('exception' => new \Exception())),
            'exception key with anything' => array(array('exception' => 'foo')),
            'foo key with non Exception object' => array(array('foo' => new \stdClass())),
        );
    }

    public function invalidContextProvider()
    {
        return array(
            'foo key with Exception' => array(array('foo' => new \Exception()), 'foo'),
        );
    }
}
