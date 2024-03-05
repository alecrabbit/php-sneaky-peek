<?php

declare(strict_types=1);

namespace AlecRabbit\Tests\TestClass;

abstract class AbstractClass
{
    protected static int $protectedProperty = 2;
    private static int $privateProperty = 1;

    protected static function protectedMethod(int $value): int
    {
        return $value;
    }

    private static function privateMethod(int $value): int
    {
        return $value;
    }
}
