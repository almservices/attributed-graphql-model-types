<?php

declare(strict_types=1);

namespace Demo;

use AlmServices\Graphql\FieldInterface;
use AlmServices\Graphql\ObjectType;

class Mutation extends ObjectType
{
    /**
     * @param iterable<FieldInterface> $mutations
     */
    public function __construct(
        iterable $mutations,
    ) {
        parent::__construct('Mutation', $mutations);
    }
}
