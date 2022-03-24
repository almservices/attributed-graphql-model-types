<?php

declare(strict_types=1);

namespace Example;

use AlmServices\Graphql\Field;
use AlmServices\Graphql\FieldInterface;
use AlmServices\Graphql\InputObjectType;

class ExampleInputType extends InputObjectType
{
    public function __construct()
    {
        parent::__construct('ExampleInputType', $this->fields());
    }

    /**
     * @return \Generator<FieldInterface>
     */
    private function fields(): \Generator
    {
        yield new Field('foo', self::nonNull(self::string()));
    }
}
