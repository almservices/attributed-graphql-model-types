<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Enum;
use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ListOf;
use AlmServices\Graphql\Model\Model;

#[Model(name: 'Foo')]
class InlineEnum
{
    #[Field]
    #[Enum('SingleEnum', ['A', 'B', 'C', 'D'])]
    public string $single;

    #[Field]
    #[ListOf('ListEnum')]
    #[Enum('ListEnum', ['A', 'B', 'C', 'D'])]
    public array $list;
}
