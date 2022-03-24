<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ListOf;
use AlmServices\Graphql\Model\Model;
use Doctrine\Common\Collections\Collection;

#[Model('ModelWithDoctrineCollection')]
class ModelWithDoctrineCollection
{
    #[Field]
    #[ListOf('string')]
    public Collection $values;

    public function __construct(Collection $values)
    {
        $this->values = $values;
    }
}
