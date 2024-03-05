<?php

declare(strict_types=1);

namespace AlecRabbit\Tests\Sneaky\Unit;

use AlecRabbit\Sneaky\Peek;
use AlecRabbit\Sneaky\ReflectionPeek;
use AlecRabbit\Tests\TestClass\HasStaticMethods;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function AlecRabbit\Tests\TestCase\Sneaky\peek;

final class FunctionTest extends TestCase
{
    #[Test]
    public function functionReturnsPeek(): void
    {
        $obj = new class {
            private function method(): string
            {
                return 'secret';
            }
        };

        $peeked = peek($obj);

        self::assertSame(Peek::class, $peeked::class);
        self::assertSame('secret', $peeked->method());
    }

    #[Test]
    public function functionReturnsReflectionPeek(): void
    {
        $peeked = peek(HasStaticMethods::class);

        self::assertSame(ReflectionPeek::class, $peeked::class);
        self::assertSame(23, $peeked->privateMethod(23));
    }

    #[Test]
    public function throwsReflectionException(): void
    {
        $this->expectException(\ReflectionException::class);
        $this->expectExceptionMessage('Class "string" does not exist');

       peek('string');
    }
}
