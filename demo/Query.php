<?php

declare(strict_types=1);

namespace Demo;

use AlmServices\Graphql\FieldInterface;
use AlmServices\Graphql\ObjectType;

class Query extends ObjectType
{
    /**
     * @param iterable<FieldInterface> $queries
     */
    public function __construct(
        iterable $queries,
    ) {
        parent::__construct('Query', $queries);
    }
}
