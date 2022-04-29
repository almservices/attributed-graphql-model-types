<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\FieldResolvable;
use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\ObjectType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\AnimalsQuery;
use Example\Attribute\IdentifiableByStringifiableObject;
use Example\Attribute\Stringifiable;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.1
 *
 * @internal
 */
class QueryTest extends TestCase
{
    private Schema $schema;

    protected function setUp(): void
    {
        parent::setUp();

        $query = new AnimalsQuery();
        $this->schema = new Schema([
            'query' => new ObjectType(
                'Query',
                static fn () => [
                    $query,
                    new FieldResolvable(
                        'identifiable',
                        new ModelType(
                            IdentifiableByStringifiableObject::class,
                            new TypeContainer(false),
                            false
                        ),
                        static fn () => new IdentifiableByStringifiableObject(new Stringifiable('my_id'))
                    ),
                ],
            ),
        ]);
    }

    public function testExecution(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            '{animals {family name id: animalId} identifiable {bar}}'
        );

        self::assertEquals([
            'data' => [
                'animals' => [
                    [
                        'family' => 'seal',
                        'name' => 'Foo',
                        'id' => '1',
                    ],
                    [
                        'family' => 'BEAR',
                        'name' => 'Bar',
                        'id' => '2',
                    ],
                ],
                'identifiable' => [
                    'bar' => 'my_id',
                ],
            ],
        ], $result->toArray());
    }
}
