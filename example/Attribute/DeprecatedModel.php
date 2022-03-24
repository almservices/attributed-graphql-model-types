<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\Model;

#[Model('DescriptionModel')]
class DeprecatedModel
{
    #[Field]
    public string $foo;
}
