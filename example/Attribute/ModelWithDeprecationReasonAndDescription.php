<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Deprecated;
use AlmServices\Graphql\Model\Description;
use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\Model;

#[Model('ModelWithDeprecationReasonAndDescription')]
class ModelWithDeprecationReasonAndDescription
{
    #[Field]
    #[Description('bar')]
    #[Deprecated('Do not use ModelWithDescription.foo anymore')]
    public string $foo;
}
