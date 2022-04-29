<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\EnumType;
use AlmServices\Graphql\FieldResolvable;
use AlmServices\Graphql\ObjectType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\StringEnumModelWithAlias;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.1
 *
 * @covers \AlmServices\Graphql\EnumType
 *
 * @internal
 */
class StringEnumModelWithAliasTest extends TestCase
{
    public function testExecution(): void
    {
        $schema = new Schema([
            'query' => new ObjectType(
                'Query',
                static fn () => [
                    new FieldResolvable(
                        'foo',
                        new EnumType(StringEnumModelWithAlias::class, new TypeContainer(false)),
                        static fn () => StringEnumModelWithAlias::BAR
                    ),
                ],
            ),
        ]);

        $result = GraphQL::executeQuery(
            $schema,
            'query {foo}'
        )->toArray();

        self::assertEquals([
            'data' => [
                'foo' => 'bar',
            ],
        ], $result);
    }
}
