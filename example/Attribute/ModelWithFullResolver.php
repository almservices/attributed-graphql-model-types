<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Argument;
use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\Model;
use Example\Context;
use Example\ExampleInputType;
use GraphQL\Type\Definition\ResolveInfo;
use JetBrains\PhpStorm\ArrayShape;

#[Model('ModelWithFullResolver')]
class ModelWithFullResolver
{
    #[Field]
    #[Argument(name: 'foo', type: 'string')]
    #[Argument(name: 'bar', type: ExampleInputType::class)]
    #[Argument(name: 'baz', type: '[Int!]!')]
    #[Argument(name: 'qux', type: '[Int!]', nullable: false)]
    public function args(#[ArrayShape([
        'foo' => 'string',
        'bar' => 'array<string, string>',
        'baz' => 'int[]',
        'qux' => 'int[]',
    ])] $args): string
    {
        return json_encode($args);
    }

    #[Field]
    public function context(#[ArrayShape([])] $args, Context $context): string
    {
        return $context->username();
    }

    #[Field]
    public function path(#[ArrayShape([])] array $args, Context $context, ResolveInfo $resolveInfo): string
    {
        return implode('.', $resolveInfo->path);
    }
}
