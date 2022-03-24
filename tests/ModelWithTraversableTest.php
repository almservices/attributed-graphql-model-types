<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\FieldResolvable;
use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\ObjectType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\ModelWithTraversable;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \AlmServices\Graphql\ModelType
 */
class ModelWithTraversableTest extends TestCase
{
    public function testExecution(): void
    {
        $schema = new Schema([
            'query' => new ObjectType(
                'Query',
                static fn () => [
                    new FieldResolvable(
                        'foo',
                        new ModelType(ModelWithTraversable::class, new TypeContainer(false), false),
                        static fn () => new ModelWithTraversable(new \ArrayObject(['a', 'b', 'c']))
                    ),
                ],
            ),
        ]);

        $result = GraphQL::executeQuery(
            schema: $schema,
            source: 'query {foo{values}}'
        )->toArray();

        self::assertEquals(expected: [
            'data' => [
                'foo' => [
                    'values' => ['a', 'b', 'c'],
                ],
            ],
        ], actual: $result);
    }
}
