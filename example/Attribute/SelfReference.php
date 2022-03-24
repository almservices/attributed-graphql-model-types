<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\Model;

#[Model('Foo')]
class SelfReference
{
    public function __construct(
        #[Field]
        public string $name,
        #[Field]
        public SelfReference $selfReference
    ) {
    }
}
