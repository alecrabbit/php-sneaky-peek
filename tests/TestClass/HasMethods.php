<?php

declare(strict_types=1);

namespace AlecRabbit\Tests\TestClass;

class HasMethods
{
    protected function protectedMethod(int $value): int
    {
        return $value;
    }

    private function privateMethod(int $value): int
    {
        return $value;
    }
}
