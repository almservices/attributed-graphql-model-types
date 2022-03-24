<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ListOf;
use AlmServices\Graphql\Model\Model;

// Goal is to check also parent's as Traversable is also iterable
#[Model('ModelWithTraversable')]
class ModelWithTraversable
{
    #[Field]
    #[ListOf('string')]
    public \Traversable $values;

    public function __construct(\Traversable $values)
    {
        $this->values = $values;
    }
}
