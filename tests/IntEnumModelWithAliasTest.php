<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\EnumType;
use AlmServices\Graphql\FieldResolvable;
use AlmServices\Graphql\ObjectType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\IntEnumModelWithAlias;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.1
 *
 * @internal
 */
class IntEnumModelWithAliasTest extends TestCase
{
    public function testExecution(): void
    {
        $schema = new Schema([
            'query' => new ObjectType(
                'Query',
                static fn () => [
                    new FieldResolvable(
                        'foo',
                        new EnumType(IntEnumModelWithAlias::class, new TypeContainer(false)),
                        static fn () => IntEnumModelWithAlias::BAR
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
