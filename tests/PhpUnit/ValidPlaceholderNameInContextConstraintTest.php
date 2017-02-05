<?php

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit_Framework_ExpectationFailedException as ExpectationFailedException;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_TestFailure as TestFailure;

class ValidPlaceholderNameInContextConstraintTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_Constraint
     */
    protected $constraint;

    protected function setUp()
    {
        $context = array(
            'invalid$character' => 'character $ is not allowed',
        );

        $this->constraint = new ValidPlaceholderNameInContextConstraint($context);
    }

    public function testConstraintDefinition()
    {
        self::assertEquals(1, count($this->constraint));
        self::assertEquals(
            'placeholder has valid name',
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
     * @param mixed $message Log message
     * @param string $placeholder Expected invalid placeholder.
     *
     * @return void
     */
    public function testInvalidMessageType($message, $placeholder)
    {
        self::assertFalse($this->constraint->evaluate($message, '', true));

        try {
            $this->constraint->evaluate($message);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertEquals(
                <<<EOF
Failed asserting that the placeholder name "$placeholder" is composed composed only of the characters A-Z, a-z, 0-9, period . and underscore _.

EOF
                ,
                TestFailure::exceptionToString($e)
            );
        }
    }

    public function validMessageTypeProvider()
    {
        return array(
            'Placeholders with invalid character are not placeholders' => array('{exist$}'),
        );
    }

    public function invalidMessageTypeProvider()
    {
        return array(
            'Placeholder invalid characters' => array('{invalid$character}', 'invalid$character'),
        );
    }
}
