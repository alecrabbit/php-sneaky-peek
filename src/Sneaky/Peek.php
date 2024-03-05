<?php

declare(strict_types=1);

namespace AlecRabbit\Sneaky;

/**
 * @template T of object
 * @mixin T
 */
final class Peek
{
    /**
     * @psalm-param T $obj
     */
    public function __construct(
        public readonly object $obj
    ) {
    }

    public function __get(string $name): mixed
    {
        return (fn(): mixed => $this->{$name})->call($this->obj);
    }

    public function __set(string $name, mixed $value): void
    {
        (fn(): mixed => $this->{$name} = $value)->call($this->obj);
    }

    public function __call(string $name, array $params = []): mixed
    {
        return (fn(): mixed => $this->{$name}(...$params))->call($this->obj);
    }
}
