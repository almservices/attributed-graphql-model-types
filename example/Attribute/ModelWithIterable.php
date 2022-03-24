<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ListOf;
use AlmServices\Graphql\Model\Model;

#[Model('ModelWithIterable')]
class ModelWithIterable
{
    #[Field]
    #[ListOf('string')]
    public iterable $values;

    public function __construct(iterable $values)
    {
        $this->values = $values;
    }
}
