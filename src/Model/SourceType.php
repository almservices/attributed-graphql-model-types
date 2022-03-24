<?php

namespace AlmServices\Graphql\Model;

class SourceType
{
    private const METHOD = 'method';
    private const PROPERTY = 'property';

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function method(): self
    {
        return new self(self::METHOD);
    }

    public static function property(): self
    {
        return new self(self::PROPERTY);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
