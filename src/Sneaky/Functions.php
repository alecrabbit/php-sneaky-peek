<?php

declare(strict_types=1);

namespace AlecRabbit\Tests\TestCase\Sneaky;


use AlecRabbit\Sneaky\Peek;
use AlecRabbit\Sneaky\ReflectionPeek;
use ReflectionClass;

use ReflectionException;

use function is_object;

if (!function_exists('peek')) {
    /**
     * @template T of object
     *
     * @psalm-param T|class-string<T> $obj
     * @return ($obj is class-string ? ReflectionPeek :  Peek<T>)
     *
     * @throws ReflectionException
     */
    function peek(object|string $obj): Peek|ReflectionPeek
    {
        if (is_object($obj) && $obj::class !== \stdClass::class) {
            return new Peek($obj);
        }

        return new ReflectionPeek(new ReflectionClass($obj));
    }
}
