<?php

declare(strict_types=1);

namespace AlecRabbit\Tests\TestClass;

class HasStaticMethods
{
    protected static function protectedMethod(int $value): int
    {
        return $value;
    }

    private static function privateMethod(int $value): int
    {
        return $value;
    }
}