<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;

class AnimalType extends ModelType
{
    public function __construct()
    {
        parent::__construct(Animal::class, new TypeContainer());
    }
}
