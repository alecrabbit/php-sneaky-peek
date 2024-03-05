<?php

declare(strict_types=1);

namespace AlecRabbit\Tests\Sneaky\Functional;

use AlecRabbit\Sneaky\Peek;
use AlecRabbit\Sneaky\ReflectionPeek;
use AlecRabbit\Tests\TestClass\HasStaticMethods;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use ReflectionException;

use function AlecRabbit\Tests\TestCase\Sneaky\peek;

final class FunctionTest extends TestCase
{
    public static function functionReturnsReflectionPeekDataProvider(): iterable
    {
        for ($i = 0; $i < 100; $i++) {
            yield [$i];
        }
    }

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
    #[DataProvider('functionReturnsReflectionPeekDataProvider')]
    public function functionReturnsReflectionPeek(int $i): void
    {
        $peeked = peek(HasStaticMethods::class);

        self::assertSame(ReflectionPeek::class, $peeked::class);
        self::assertSame($i, $peeked->privateMethod($i));
    }
    
   #[Test]
    public function functionReturnsReflectionPeekOnStdClassObject(): void
    {
        $peeked = peek(new \stdClass());

        self::assertSame(ReflectionPeek::class, $peeked::class);
    }

    #[Test]
    public function throwsReflectionException(): void
    {
        $this->expectException(ReflectionException::class);
        $this->expectExceptionMessage('Class "string" does not exist');

        peek('string');
    }
}
