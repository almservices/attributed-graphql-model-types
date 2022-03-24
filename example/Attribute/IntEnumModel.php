<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Model;

#[Model('StringEnumModel')]
enum IntEnumModel: int
{
    case FOO = 1;

    case BAR = 2;
}
