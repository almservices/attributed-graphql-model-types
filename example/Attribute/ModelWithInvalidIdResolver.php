<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ID;
use AlmServices\Graphql\Model\Model;

#[Model('Foo')]
class ModelWithInvalidIdResolver
{
    #[ID]
    #[Field]
    public function bar(): array
    {
        return [];
    }
}
