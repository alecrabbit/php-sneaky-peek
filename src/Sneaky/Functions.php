<?php

declare(strict_types=1);

namespace AlecRabbit\Tests\TestCase\Sneaky;


use AlecRabbit\Sneaky\Peek;

if (! function_exists('peek')) {
    /**
     * @template T of object
     *
     * @psalm-param T|class-string<T> $obj
     * @return Peek<T>
     */
    function peek(object|string $obj): Peek
    {
        return new Peek($obj);
    }
}
