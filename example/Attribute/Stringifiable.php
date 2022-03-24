<?php

declare(strict_types=1);

namespace Example\Attribute;

class Stringifiable implements \Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
