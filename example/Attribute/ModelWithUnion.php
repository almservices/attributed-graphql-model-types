<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\Model;

#[Model('ModelWithUnion')]
class ModelWithUnion
{
    #[Field]
    public \Traversable & \Stringable $foo;
}
