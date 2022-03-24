<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\QueryInterface;
use GraphQL\Type\Definition\Type;

class AnimalsQuery implements QueryInterface
{
    public function name(): string
    {
        return 'animals';
    }

    public function type(): Type
    {
        return Type::nonNull(Type::listOf(Type::nonNull(new AnimalType())));
    }

    public function resolver(): callable
    {
        return static fn () => [
            new Animal(1, 'Foo', 'Swojaki', Family::SEAL, [56.756, 82.334]),
            new Animal(2, 'Bar', 'Swojaki', Family::BEAR, [56.756, 82.334]),
        ];
    }
}
