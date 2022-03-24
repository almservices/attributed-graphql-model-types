<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

class FieldBuilderStrategy
{
    private int $strategy;

    public function __construct(int $strategy)
    {
        $this->strategy = $strategy;
    }

    public static function file(): self
    {
        return new self(1);
    }

    public static function virtual(): self
    {
        return new self(2);
    }

    public static function hybrid(): self
    {
        return new self(3);
    }

    public function equals(self $other): bool
    {
        return $this->strategy === $other->strategy;
    }

    public function is(FieldBuilderStrategy $other): bool
    {
        return ($this->strategy & $other->strategy) === $other->strategy;
    }
}
