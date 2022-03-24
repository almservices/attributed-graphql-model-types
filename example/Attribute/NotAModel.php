<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Field;

class NotAModel
{
    #[Field]
    public string $id;
}
