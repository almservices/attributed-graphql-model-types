<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\EnumType;
use AlmServices\Graphql\FieldResolvable;
use AlmServices\Graphql\ObjectType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\StringEnumModel;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \AlmServices\Graphql\EnumType
 */
class StringEnumModelTest extends TestCase
{
    public function testExecution(): void
    {
        $schema = new Schema([
            'query' => new ObjectType(
                'Query',
                static fn () => [
                    new FieldResolvable(
                        'foo',
                        new EnumType(StringEnumModel::class, new TypeContainer(false)),
                        static fn () => StringEnumModel::BAR
                    ),
                ],
            ),
        ]);

        $result = GraphQL::executeQuery(
            schema: $schema,
            source: 'query {foo}'
        )->toArray();

        self::assertEquals(expected: [
            'data' => [
                'foo' => 'BAR',
            ],
        ], actual: $result);
    }
}
