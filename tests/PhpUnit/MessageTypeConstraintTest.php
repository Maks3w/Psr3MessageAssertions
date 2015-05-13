<?php

namespace FR3D\Psr3MessagesAssertionsTest\PhpUnit;

use FR3D\Psr3MessagesAssertions\PhpUnit\MessageTypeConstraint;
use FR3D\Psr3MessagesAssertionsTest\Fixture\ObjectWithToString;
use PHPUnit_Framework_ExpectationFailedException as ExpectationFailedException;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_TestFailure as TestFailure;
use stdClass;

class MessageTypeConstraintTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_Constraint
     */
    protected $constraint;

    protected function setUp()
    {
        $this->constraint = new MessageTypeConstraint();
    }

    public function testConstraintDefinition()
    {
        self::assertEquals(1, count($this->constraint));
        self::assertEquals(
            'is string or a object with a __toString() method',
            $this->constraint->toString()
        );
    }

    /**
     * @dataProvider validMessageTypeProvider
     *
     * @param string|object $message
     *
     * @return void
     */
    public function testValidMessageType($message)
    {
        self::assertTrue($this->constraint->evaluate($message, '', true));
    }

    /**
     * @dataProvider invalidMessageTypeProvider
     *
     * @param mixed $message
     * @param string $type String representation of $message argument type.
     *
     * @return void
     */
    public function testInvalidMessageType($message, $type)
    {
        self::assertFalse($this->constraint->evaluate($message, '', true));

        try {
            $this->constraint->evaluate($message);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertEquals(
                <<<EOF
Failed asserting that $type is string or a object with a __toString() method.

EOF
                ,
                TestFailure::exceptionToString($e)
            );
        }
    }

    public function validMessageTypeProvider()
    {
        return array(
            'string' => array('message'),
            'object with toString' => array(new ObjectWithToString()),
            'integer' => array(1),
            'double' => array(1.1),
            'null' => array(null),
        );
    }

    public function invalidMessageTypeProvider()
    {
        return array(
            'array' => array(array(), 'array'),
            'function' => array(function () {}, 'object'),
            'object without toString' => array(new stdClass(), 'object'),
        );
    }
}
