<?php

declare(strict_types=1);

namespace AlecRabbit\Sneaky;

use AlecRabbit\Sneaky\Exception\MethodDoesNotExist;
use AlecRabbit\Sneaky\Exception\NoInstance;
use AlecRabbit\Sneaky\Exception\PropertyDoesNotExist;
use ReflectionClass;
use stdClass;

/**
 * @template T of object
 * @mixin T
 */
final class ReflectionPeek
{
    private readonly object $instance;

    /**
     * @param ReflectionClass<T> $reflection
     * @throws \ReflectionException
     */
    public function __construct(
        private readonly ReflectionClass $reflection
    ) {
        $this->instance = $reflection->isInstantiable()
            ? $reflection->newInstanceWithoutConstructor()
            : new stdClass();
    }

    public function __get(string $name): mixed
    {
        if ($this->reflection->hasProperty($name)) {
            if ($this->reflection->getProperty($name)->isStatic()) {
                return $this->reflection->getStaticPropertyValue($name);
            }
            
            $this->checkInstance();
            
            return (fn(): mixed => $this->{$name})->call($this->instance);
        }

        if ($this->reflection->hasConstant($name)) {
            return $this->reflection->getConstant($name);
        }

        throw new PropertyDoesNotExist(
            sprintf(
                'Property [%s] does not exist in [%s].',
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
            
            $this->checkInstance();
            
            (fn(): mixed => $this->{$name} = $value)->call($this->instance);
            return;
        }

        throw new PropertyDoesNotExist(
            sprintf(
                'Property [%s] does not exist in [%s].',
                $name,
                $this->reflection->getName()
            )
        );
    }

    public function __call(string $name, array $params = []): mixed
    {
        if ($this->reflection->hasMethod($name)) {
            if ($this->reflection->getMethod($name)->isStatic()) {
                return $this->reflection->getMethod($name)->invoke(null, ...$params);
            }

            $this->checkInstance();

            return (fn(): mixed => $this->{$name}(...$params))->call($this->instance);
        }

        throw new MethodDoesNotExist(
            sprintf(
                'Method [%s] does not exist in [%s].',
                $name,
                $this->reflection->getName(),
            )
        );
    }

    protected function checkInstance(): void
    {
        if ($this->instance instanceof stdClass) {
            throw new NoInstance(
                sprintf(
                    'No instance for [%s].',
                    $this->reflection->getName(),
                )
            );
        }
    }
}
