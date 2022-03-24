<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Description;
use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\Model;

#[Model('ModelWithDescription')]
class ModelWithDescription
{
    #[Field]
    #[Description('Bar')]
    public string $foo;
}
