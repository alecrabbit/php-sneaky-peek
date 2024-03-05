<?php

declare(strict_types=1);

namespace AlecRabbit\Tests\TestClass;

abstract class AbstractClass
{
    protected static int $staticValue = 1;
    protected int $value = 1;

    protected static function staticPlus(int $inc): int {
        return self::$staticValue + $inc;
    }
    protected function plus(int $inc): int {
        return $this->value + $inc;
    }
}
