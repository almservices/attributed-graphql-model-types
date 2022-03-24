<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Alias;
use AlmServices\Graphql\Model\Model;

#[Model('IntEnumModelWithAlias')]
enum IntEnumModelWithAlias: int
{
    #[Alias('foo')]
    case FOO = 1;
    #[Alias('bar')]
    case BAR = 2;
}
