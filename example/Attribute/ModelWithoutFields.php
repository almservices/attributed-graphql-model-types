<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Alias;
use AlmServices\Graphql\Model\ID;
use AlmServices\Graphql\Model\ListOf;
use AlmServices\Graphql\Model\Model;
use AlmServices\Graphql\Model\NonNull;

#[Model('ModelWithoutFields')]
class ModelWithoutFields
{
    #[ID]
    public int $id;

    /**
     * @var array<string>
     */
    #[ListOf('string')]
    public array $foo;

    #[NonNull]
    public ?string $bar;

    #[Alias('qux')]
    public ?string $baz;
}
