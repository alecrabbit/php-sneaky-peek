<?php

declare(strict_types=1);

namespace AlecRabbit\Tests\Sneaky\Unit;

use AlecRabbit\Sneaky\Peek;
use AlecRabbit\Tests\TestClass\WithDynamicProperties;
use Error;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

final class PeekTest extends TestCase
{
    #[Test]
    public function canBeInstantiated(): void
    {
        $peek = $this->getTesteeInstance();

        self::assertSame(
            Peek::class,
            $peek::class,
        );
    }

    protected function getTesteeInstance(
        ?object $o = null
    ): Peek {
        return new Peek($o ?? new stdClass());
    }

    #[Test]
    public function canGetPrivateProperty(): void
    {
        $o = new class {
            private string $property = 'value';
        };

        $peek = $this->getTesteeInstance($o);

        self::assertSame('value', $peek->property);
    }

    #[Test]
    public function canSetPrivateProperty(): void
    {
        $o = new class {
            private string $property = 'value';
        };

        $peek = $this->getTesteeInstance($o);

        $peek->property = 'new value';

        self::assertSame('new value', $peek->property);
    }

    #[Test]
    public function canCallPrivateMethod(): void
    {
        $o = new class {
            private function method(string $arg): string
            {
                return $arg;
            }
        };

        $peek = $this->getTesteeInstance($o);

        self::assertSame('value', $peek->method('value'));
    }

    #[Test]
    public function canCallPrivateMethodWithArguments(): void
    {
        $o = new class {
            private function method(string $arg1, string $arg2): string
            {
                return $arg1 . $arg2;
            }
        };

        $peek = $this->getTesteeInstance($o);

        self::assertSame('value1value2', $peek->method('value1', 'value2'));
    }

    #[Test]
    public function canGetProtectedProperty(): void
    {
        $o = new class {
            protected string $property = 'value';
        };

        $peek = $this->getTesteeInstance($o);

        self::assertSame('value', $peek->property);
    }

    #[Test]
    public function canSetProtectedProperty(): void
    {
        $o = new class {
            protected string $property = 'value';
        };

        $peek = $this->getTesteeInstance($o);

        $peek->property = 'new value';

        self::assertSame('new value', $peek->property);
    }

    #[Test]
    public function canCallProtectedMethod(): void
    {
        $o = new class {
            protected function method(string $arg): string
            {
                return $arg;
            }
        };

        $peek = $this->getTesteeInstance($o);

        self::assertSame('value', $peek->method('value'));
    }

    #[Test]
    public function canCallProtectedMethodWithArguments(): void
    {
        $o = new class {
            protected function method(string $arg1, string $arg2): string
            {
                return $arg1 . $arg2;
            }
        };

        $peek = $this->getTesteeInstance($o);

        self::assertSame('value1value2', $peek->method('value1', 'value2'));
    }

    #[Test]
    public function throwsOnMethodCallIfMethodIsUndefined(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Call to undefined method ');

        $o = new class {
        };

        $peek = $this->getTesteeInstance($o);

        self::assertSame('1', $peek->nonexistent('1'));
    }

    #[Test]
    public function throwsOnGetUndefinedProperty(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Undefined property: ');

        $o = new class {
        };

        $peek = $this->getTesteeInstance($o);

        self::assertSame('1', $peek->nonexistent);
    }

    #[Test]
    public function canSetUndefinedPropertyWithAllowedDynamic(): void
    {
        $o = new WithDynamicProperties();

        $peek = $this->getTesteeInstance($o);

        $peek->nonexistent = 1;

        self::assertSame(1, $peek->nonexistent);
    }

    #[Test]
    public function throwsOnSetUndefinedPropertyNoDynamic(): void
    {
        if (PHP_VERSION_ID >= 80300) {
            $this->expectException(Exception::class);
            $this->expectExceptionMessage('Creation of dynamic property ');

            $o = new class {
            };

            $peek = $this->getTesteeInstance($o);

            $peek->nonexistent = 1;
            return;
        }

        self::markTestSkipped('Test skipped for PHP versions below [8.3].');
    }

    #[Test]
    public function throwsIfMethodDoesNotExist(): void
    {
        $peek = $this->getTesteeInstance(new stdClass());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot bind closure to scope of internal class stdClass');

        $peek->nonExistentMethod();
    }
}
