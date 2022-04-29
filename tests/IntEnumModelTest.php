<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\EnumType;
use AlmServices\Graphql\FieldResolvable;
use AlmServices\Graphql\ObjectType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\IntEnumModel;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.1
 *
 * @internal
 */
class IntEnumModelTest extends TestCase
{
    public function testExecution(): void
    {
        $schema = new Schema([
            'query' => new ObjectType(
                'Query',
                static fn () => [
                    new FieldResolvable(
                        'foo',
                        new EnumType(IntEnumModel::class, new TypeContainer(false)),
                        static fn () => IntEnumModel::BAR
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
                'foo' => 'BAR',
            ],
        ], $result);
    }
}
