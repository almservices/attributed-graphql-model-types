<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\Type;

class Field implements FieldInterface
{
    private string $name;

    /** @var NullableType|Type|(\Closure(): (NullableType|Type)) */
    private $type;

    /**
     * @param NullableType|Type|(\Closure(): (NullableType|Type)) $type
     */
    public function __construct(string $name, $type)
    {
        $this->name = $name;
        $this->type = is_callable($type) ? \Closure::fromCallable($type) : $type;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function type()
    {
        return $this->type;
    }
}
