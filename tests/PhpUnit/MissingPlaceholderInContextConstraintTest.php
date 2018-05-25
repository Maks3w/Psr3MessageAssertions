<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestFailure;

class MissingPlaceholderInContextConstraintTest extends TestCase
{
    /**
     * @var Constraint
     */
    protected $constraint;

    protected function setUp()
    {
        $context = [
            'exists' => 'baz',
            'Exists' => 'baz',
            'exists.' => 'baz',
            'exists_' => 'baz',
            'invalid$character' => 'character $ is not allowed',
        ];

        $this->constraint = new MissingPlaceholderInContextConstraint($context);
    }

    public function testConstraintDefinition()
    {
        self::assertEquals(1, count($this->constraint));
        self::assertEquals(
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
Failed asserting that the placeholder "$placeholder" exists in the context array.

EOF
                ,
                TestFailure::exceptionToString($e)
            );
        }
    }

    public function validMessageTypeProvider()
    {
        return [
            'Without placeholders' => ['any'],
            'Placeholder [a-zA-Z_.] exists in context' => ['{exists}{Exists}{exists_}{exists.}'],
            'Placeholders with invalid character are not placeholders' => ['{exist$}'],
        ];
    }

    public function invalidMessageTypeProvider()
    {
        return [
            'Placeholder without context' => ['{not_exists}', 'not_exists'],
        ];
    }
}
