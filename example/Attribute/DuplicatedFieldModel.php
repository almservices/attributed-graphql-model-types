<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Alias;
use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\Model;

#[Model('DuplicatedFieldModel')]
class DuplicatedFieldModel
{
    #[Field]
    public int $foo;

    #[Field]
    #[Alias('foo')]
    public int $bar;
}
