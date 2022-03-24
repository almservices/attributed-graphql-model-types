<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Alias;
use AlmServices\Graphql\Model\Model;

#[Model('StringEnumModelWithAlias')]
enum StringEnumModelWithAlias: string
{
    #[Alias('foo')]
    case FOO = 'foo';
    #[Alias('bar')]
    case BAR = 'bar';
}
