<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use FR3D\Psr3MessagesAssertions\Fixture\ObjectWithToString;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestFailure;
use stdClass;

class MessageTypeConstraintTest extends TestCase
{
    /**
     * @var Constraint
     */
    protected $constraint;

    protected function setUp(): void
    {
        $this->constraint = new MessageTypeConstraint();
    }

    public function testConstraintDefinition(): void
    {
        self::assertCount(1, $this->constraint);
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
    public function testValidMessageType($message): void
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
    public function testInvalidMessageType($message, $type): void
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

    public function validMessageTypeProvider(): array
    {
        return [
            'string' => ['message'],
            'object with toString' => [new ObjectWithToString()],
            'integer' => [1],
            'double' => [1.1],
            'null' => [null],
        ];
    }

    public function invalidMessageTypeProvider(): array
    {
        return [
            'array' => [[], 'array'],
            'function' => [function () {
            }, 'object'],
            'object without toString' => [new stdClass(), 'object'],
        ];
    }
}
