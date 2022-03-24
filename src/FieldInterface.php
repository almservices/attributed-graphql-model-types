<?php

namespace AlmServices\Graphql;

use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\Type;

interface FieldInterface
{
    public function name(): string;

    /**
     * @return NullableType|Type|(\Closure(): (NullableType|Type)) $type
     */
    public function type();
}
