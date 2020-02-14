<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\PhpUnit;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestFailure;

class ExceptionsInContextConstraintTest extends TestCase
{
    /**
     * @var Constraint
     */
    protected $constraint;

    protected function setUp(): void
    {
        $this->constraint = new ExceptionsInContextConstraint();
    }

    public function testConstraintDefinition(): void
    {
        self::assertCount(1, $this->constraint);
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
    public function testValidConstraint($context): void
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
    public function testInvalidConstraint($message, $invalidKey): void
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

    public function validContextProvider(): array
    {
        return [
            'exception key with Exception' => [['exception' => new \Exception()]],
            'exception key with anything' => [['exception' => 'foo']],
            'foo key with non Exception object' => [['foo' => new \stdClass()]],
        ];
    }

    public function invalidContextProvider(): array
    {
        return [
            'foo key with Exception' => [['foo' => new \Exception()], 'foo'],
        ];
    }
}
