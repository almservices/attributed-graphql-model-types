<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\FieldResolvable;
use AlmServices\Graphql\ObjectType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\ModelWithFullResolver;
use Example\Context;
use Example\ExampleInputType;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.0
 *
 * @internal
 * @covers \AlmServices\Graphql\ModelType
 * @covers \AlmServices\Graphql\TypeContainer
 */
class ModelResolverTest extends TestCase
{
    private Schema $schema;

    protected function setUp(): void
    {
        parent::setUp();

        $typeContainer = new TypeContainer(false);
        $typeContainer->set(ExampleInputType::class, new ExampleInputType());
        $type = $typeContainer->get(ModelWithFullResolver::class);
        $this->schema = new Schema([
            'query' => new ObjectType(
                'Query',
                [
                    new FieldResolvable('foo', $type, static fn () => new ModelWithFullResolver()),
                ]
            ),
        ]);
    }

    public function testArgs(): void
    {
        $result = GraphQL::executeQuery($this->schema, 'query {foo {args(foo: "bar", bar: {foo: "string"}, baz: [1], qux: [2])}}', null, new Context('bar'))->toArray();
        self::assertEquals([
            'data' => [
                'foo' => [
                    'args' => '{"foo":"bar","bar":{"foo":"string"},"baz":[1],"qux":[2]}',
                ],
            ],
        ], $result);
    }

    public function testContext(): void
    {
        $result = GraphQL::executeQuery($this->schema, 'query {foo {context}}', null, new Context('bar'))->toArray();
        self::assertEquals([
            'data' => [
                'foo' => [
                    'context' => 'bar',
                ],
            ],
        ], $result);
    }
}
