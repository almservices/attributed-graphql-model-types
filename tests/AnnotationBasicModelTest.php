<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\AttributeResolver;
use AlmServices\Graphql\FieldResolvable;
use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\ObjectType;
use AlmServices\Graphql\TypeContainer;
use Example\Annotation\BasicModel;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class AnnotationBasicModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        AttributeResolver::$annotations = true;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        AttributeResolver::$annotations = false;
    }

    public function testExecution(): void
    {
        $type = new ModelType(BasicModel::class, new TypeContainer(false), false);

        $data = new BasicModel(1, 'bar', 'B');

        $schema = new Schema([
            'query' => new ObjectType(
                'Query',
                static fn () => [
                    new FieldResolvable(
                        'foo',
                        $type,
                        static fn () => $data
                    ),
                ],
            ),
        ]);

        $result = GraphQL::executeQuery(
            $schema,
            'query {foo{id name: nameAlias state resolver(foo: "baz")}}'
        )->toArray();

        self::assertEquals([
            'data' => ['foo' => ['id' => '1', 'name' => 'bar', 'state' => 'B', 'resolver' => 'baz']],
        ], $result);
    }
}
