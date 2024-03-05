<?php

declare(strict_types=1);

namespace AlecRabbit\Tests\Sneaky\Unit;

use AlecRabbit\Sneaky\ReflectionPeek;
use AlecRabbit\Tests\TestClass\HasMethods;
use AlecRabbit\Tests\TestClass\HasProperties;
use AlecRabbit\Tests\TestClass\HasStaticMethods;
use AlecRabbit\Tests\TestClass\HasStaticProperties;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ReflectionPeekTest extends TestCase
{
    #[Test]
    public function canBeInstantiated(): void
    {
        $peek = $this->getTesteeInstance();

        self::assertSame(
            ReflectionPeek::class,
            $peek::class,
        );
    }

    protected function getTesteeInstance(
        ?\ReflectionClass $reflection = null,
    ): ReflectionPeek {
        return new ReflectionPeek(
            $reflection ?? new \ReflectionClass(new \stdClass())
        );
    }

    #[Test]
    public function canGetStaticProperties(): void
    {
        $class = HasStaticProperties::class;

        $peek = $this->getTesteeInstance(new \ReflectionClass($class));

        self::assertSame('private', $peek->privateProperty);
        self::assertSame('protected', $peek->protectedProperty);
    }

    #[Test]
    public function canGetProperties(): void
    {
        $class = HasProperties::class;

        $peek = $this->getTesteeInstance(new \ReflectionClass($class));

        self::assertSame('private', $peek->privateProperty);
        self::assertSame('protected', $peek->protectedProperty);
    }

    #[Test]
    public function canSetStaticProperties(): void
    {
        $class = HasStaticProperties::class;

        $peek = $this->getTesteeInstance(new \ReflectionClass($class));

        $peek->privateProperty = 'new private';
        $peek->protectedProperty = 'new protected';

        self::assertSame('new private', $peek->privateProperty);
        self::assertSame('new protected', $peek->protectedProperty);

        // reset to original values
        $peek->privateProperty = 'private';
        $peek->protectedProperty = 'protected';
    }

    #[Test]
    public function canSetProperties(): void
    {
        $class = HasProperties::class;

        $peek = $this->getTesteeInstance(new \ReflectionClass($class));

        $peek->privateProperty = 'new private';
        $peek->protectedProperty = 'new protected';

        self::assertSame('new private', $peek->privateProperty);
        self::assertSame('new protected', $peek->protectedProperty);
    }

    #[Test]
    public function canCallStaticMethods(): void
    {
        $class = HasStaticMethods::class;

        $peek = $this->getTesteeInstance(new \ReflectionClass($class));

        self::assertSame(2, $peek->privateMethod(2));
        self::assertSame(1, $peek->protectedMethod(1));
    }

    #[Test]
    public function canCallMethods(): void
    {
        $class = HasMethods::class;

        $peek = $this->getTesteeInstance(new \ReflectionClass($class));

        self::assertSame(1, $peek->privateMethod(1));
        self::assertSame(2, $peek->protectedMethod(2));
    }

    #[Test]
    public function canGetStaticPropertiesWithObject(): void
    {
        $class = HasStaticProperties::class;
        $object = new $class();

        $peek = $this->getTesteeInstance(new \ReflectionClass($object));

        self::assertSame('private', $peek->privateProperty);
        self::assertSame('protected', $peek->protectedProperty);
    }
    
    #[Test]
    public function canGetPropertiesWithObject(): void
    {
        $class = HasProperties::class;
        $object = new $class();

        $peek = $this->getTesteeInstance(new \ReflectionClass($object));

        self::assertSame('private', $peek->privateProperty);
        self::assertSame('protected', $peek->protectedProperty);
    }

    #[Test]
    public function canSetStaticPropertiesWithObject(): void
    {
        $class = HasStaticProperties::class;
        $object = new $class();

        $peek = $this->getTesteeInstance(new \ReflectionClass($object));

        $peek->privateProperty = 'new private';
        $peek->protectedProperty = 'new protected';

        self::assertSame('new private', $peek->privateProperty);
        self::assertSame('new protected', $peek->protectedProperty);

        // reset to original values
        $peek->privateProperty = 'private';
        $peek->protectedProperty = 'protected';
    }
    
    #[Test]
    public function canSetPropertiesWithObject(): void
    {
        $class = HasProperties::class;
        $object = new $class();

        $peek = $this->getTesteeInstance(new \ReflectionClass($object));

        $peek->privateProperty = 'new private';
        $peek->protectedProperty = 'new protected';

        self::assertSame('new private', $peek->privateProperty);
        self::assertSame('new protected', $peek->protectedProperty);
    }
    
    #[Test]
    public function canCallStaticMethodsWithObject(): void
    {
        $class = HasStaticMethods::class;
        $object = new $class();

        $peek = $this->getTesteeInstance(new \ReflectionClass($object));

        self::assertSame(2, $peek->privateMethod(2));
        self::assertSame(1, $peek->protectedMethod(1));
    }
    
    #[Test]
    public function canCallMethodsWithObject(): void
    {
        $class = HasMethods::class;
        $object = new $class();
        
        $peek = $this->getTesteeInstance(new \ReflectionClass($object));
        
        self::assertSame(1, $peek->privateMethod(1));
        self::assertSame(2, $peek->protectedMethod(2));
    }
    
    #[Test]
    public function throwsIfPropertyDoesNotExistOnGet(): void
    {
        $peek = $this->getTesteeInstance(new \ReflectionClass(new \stdClass()));

        $this->expectException(\AlecRabbit\Sneaky\Exception\PropertyDoesNotExist::class);
        $this->expectExceptionMessage('Property [nonExistentProperty] does not exist in [stdClass]');

        $peek->nonExistentProperty;
    }    
    #[Test]
    public function throwsIfPropertyDoesNotExistOnSet(): void
    {
        $peek = $this->getTesteeInstance(new \ReflectionClass(new \stdClass()));

        $this->expectException(\AlecRabbit\Sneaky\Exception\PropertyDoesNotExist::class);
        $this->expectExceptionMessage('Property [nonExistentProperty] does not exist in [stdClass]');

        $peek->nonExistentProperty = 1;
    }
    
    #[Test]
    public function throwsIfMethodDoesNotExist(): void
    {
        $peek = $this->getTesteeInstance(new \ReflectionClass(new \stdClass()));

        $this->expectException(\AlecRabbit\Sneaky\Exception\MethodDoesNotExist::class);
        $this->expectExceptionMessage('Method [nonExistentMethod] does not exist in [stdClass]');

        $peek->nonExistentMethod();
    }
}
