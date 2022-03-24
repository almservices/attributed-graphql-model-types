<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ListOf;
use AlmServices\Graphql\Model\Model;

#[Model('ModelWithArray')]
class ModelWithArray
{
    #[Field]
    #[ListOf('string')]
    public array $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }
}
