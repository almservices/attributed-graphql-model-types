<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ListOf;
use AlmServices\Graphql\Model\Model;

#[Model('Foo')]
class ModelWithInvalidList
{
    #[Field]
    #[ListOf(type: 'string')]
    public int $bar;
}
