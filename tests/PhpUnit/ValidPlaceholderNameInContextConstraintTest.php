<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestFailure;

class ValidPlaceholderNameInContextConstraintTest extends TestCase
{
    /**
     * @var Constraint
     */
    protected $constraint;

    protected function setUp(): void
    {
        $context = [
            'invalid$character' => 'character $ is not allowed',
        ];

        $this->constraint = new ValidPlaceholderNameInContextConstraint($context);
    }

    public function testConstraintDefinition()
    {
        self::assertCount(1, $this->constraint);
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
        return [
            'Placeholders with invalid character are not placeholders' => ['{exist$}'],
        ];
    }

    public function invalidMessageTypeProvider()
    {
        return [
            'Placeholder invalid characters' => ['{invalid$character}', 'invalid$character'],
        ];
    }
}
