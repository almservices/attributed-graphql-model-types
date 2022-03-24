<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\Arguments;
use AlmServices\Graphql\Field;
use AlmServices\Graphql\FieldArgumentable;
use AlmServices\Graphql\FieldDefaultValue;
use AlmServices\Graphql\FieldMapping;
use AlmServices\Graphql\ObjectType;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \AlmServices\Graphql\Arguments
 * @covers \AlmServices\Graphql\FieldArgumentable
 * @covers \AlmServices\Graphql\ObjectType
 */
class MutationTest extends TestCase
{
    private Schema $schema;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schema = new Schema([
            'mutation' => new ObjectType(
                'Mutation',
                [
                    new FieldArgumentable(
                        name: 'createDogeMeme',
                        type: new ObjectType(
                            'Meme',
                            static fn () => [
                                new Field(
                                    'url',
                                    Type::nonNull(Type::string())
                                ),
                            ],
                        ),
                        resolver: static fn ($root, array $args) => [
                            'url' => sprintf(
                                'https://api.memegen.link/images/doge/%s/%s.png',
                                urlencode($args['top']),
                                urlencode($args['bot']),
                            ),
                        ],
                        args: new Arguments(
                            new class() extends Field implements FieldMapping,
                            FieldDefaultValue {
                                public function __construct()
                                {
                                    parent::__construct('top', Type::nonNull(Type::string()));
                                }

                                /**
                                 * @param string $input
                                 */
                                public function map($input): string
                                {
                                    return strtoupper($input);
                                }

                                public function defaultValue(): string
                                {
                                    return '';
                                }
                            },
                            new class() extends Field implements FieldDefaultValue {
                                public function __construct()
                                {
                                    parent::__construct('bot', Type::string());
                                }

                                public function defaultValue(): string
                                {
                                    return 'my bot text';
                                }
                            },
                        )
                    ),
                ]
            ),
        ]);
    }

    public function testExecution(): void
    {
        $result = GraphQL::executeQuery(
            schema: $this->schema,
            source: 'mutation {createDogeMeme(top: "my top text") { url }}'
        );

        // MY+TOP+TEXT is uppercase because we used field mapping
        // my+bot+text comes from default field value

        self::assertEquals([
            'data' => [
                'createDogeMeme' => [
                    'url' => 'https://api.memegen.link/images/doge/MY+TOP+TEXT/my+bot+text.png',
                ],
            ],
        ], $result->toArray());
    }
}
