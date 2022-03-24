<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Alias;
use AlmServices\Graphql\Model\Model;

#[Model('EnumClash')]
enum EnumClash
{
    case FOO;
    #[Alias('FOO')]
    case BAR;
}
