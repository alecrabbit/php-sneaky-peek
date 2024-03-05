<?php

declare(strict_types=1);

namespace AlecRabbit\Sneaky;

use AlecRabbit\Sneaky\Exception\MethodDoesNotExist;
use AlecRabbit\Sneaky\Exception\PropertyDoesNotExist;

final class ReflectionPeek
{
    private readonly object $obj;
    public function __construct(
        private readonly \ReflectionClass $reflection
    ) {
        $this->obj = $reflection->newInstanceWithoutConstructor();
    }

    public function __get(string $name): mixed
    {
        if ($this->reflection->hasProperty($name)) {
            if ($this->reflection->getProperty($name)->isStatic()) {
                return $this->reflection->getStaticPropertyValue($name);
            }
            return (fn() => $this->{$name})->call($this->obj);
        }

        throw new PropertyDoesNotExist(
            sprintf(
                'Property [%s] does not exist in [%s]',
                $name,
                $this->reflection->getName()
            )
        );
    }

    public function __set(string $name, mixed $value): void
    {
        if ($this->reflection->hasProperty($name)) {
            if ($this->reflection->getProperty($name)->isStatic()) {
                $this->reflection->setStaticPropertyValue($name, $value);
                return;
            }
            (fn() => $this->{$name} = $value)->call($this->obj);
            return;
        }

        throw new PropertyDoesNotExist(
            sprintf(
                'Property [%s] does not exist in [%s]',
                $name,
                $this->reflection->getName()
            )
        );
    }

    public function __call(string $name, array $params = []): mixed
    {
        if($this->reflection->hasMethod($name)) {
            if($this->reflection->getMethod($name)->isStatic()) {
                return $this->reflection->getMethod($name)->invoke(null, ...$params);
            }
            return (fn() => $this->{$name}(...$params))->call($this->obj);
        }

        throw new MethodDoesNotExist(
            sprintf(
                'Method [%s] does not exist in [%s]',
                $name,
                $this->reflection->getName(),
            )
        );
    }
}
