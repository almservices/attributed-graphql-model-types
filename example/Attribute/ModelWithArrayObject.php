<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ListOf;
use AlmServices\Graphql\Model\Model;

// Goal is to check also parent's as Traversable is also iterable
#[Model('ModelWithArrayObject')]
class ModelWithArrayObject
{
    #[Field]
    #[ListOf('string')]
    public \ArrayObject $values;

    public function __construct(\ArrayObject $values)
    {
        $this->values = $values;
    }
}
