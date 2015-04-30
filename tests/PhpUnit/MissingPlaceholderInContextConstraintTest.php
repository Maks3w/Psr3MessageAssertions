<?php

namespace FR3D\Psr3MessagesAssertionsTest\PhpUnit;

use FR3D\Psr3MessagesAssertions\PhpUnit\MissingPlaceholderInContextConstraint;
use PHPUnit_Framework_ExpectationFailedException as ExpectationFailedException;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_TestFailure as TestFailure;

class MissingPlaceholderInContextConstraintTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_Constraint
     */
    protected $constraint;

    protected function setUp()
    {
        $context = array(
            'exists' => 'baz',
            'Exists' => 'baz',
            'exists.' => 'baz',
            'exists_' => 'baz',
            'invalid$character' => 'character $ is not allowed',
        );

        $this->constraint = new MissingPlaceholderInContextConstraint($context);
    }

    public function testConstraintDefinition()
    {
        $this->assertEquals(1, count($this->constraint));
        $this->assertEquals(
            'placeholder exists in the context array',
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
        $this->assertTrue($this->constraint->evaluate($message, '', true));
    }

    /**
     * @dataProvider invalidMessageTypeProvider
     *
     * @param mixed $message Log message
     * @param string $placeholder Expected invalid placeholder.
     *
     * @return void
     */
    public function testInvalidMessageType($message, $placeholder)
    {
        $this->assertFalse($this->constraint->evaluate($message, '', true));

        try {
            $this->constraint->evaluate($message);
            $this->fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
Failed asserting that the placeholder "$placeholder" exists in the context array.

EOF
                ,
                TestFailure::exceptionToString($e)
            );
        }
    }

    public function validMessageTypeProvider()
    {
        return array(
            'Without placeholders' => array('any'),
            'Placeholder [a-zA-Z_.] exists in context' => array('{exists}{Exists}{exists_}{exists.}'),
            'Placeholders with invalid character are not placeholders' => array('{exist$}'),
        );
    }

    public function invalidMessageTypeProvider()
    {
        return array(
            'Placeholder without context' => array('{not_exists}', 'not_exists'),
        );
    }
}
