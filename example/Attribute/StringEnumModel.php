<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Model;

#[Model('StringEnumModel')]
enum StringEnumModel: string
{
    case FOO = 'foo';

    case BAR = 'bar';
}
