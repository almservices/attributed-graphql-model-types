<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ID;
use AlmServices\Graphql\Model\Model;

#[Model('Foo')]
class IdentifiableByStringifiableObject
{
    #[ID]
    #[Field]
    public Stringifiable $bar;

    public function __construct(Stringifiable $bar)
    {
        $this->bar = $bar;
    }
}
