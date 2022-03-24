<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\TypeContainer;
use Example\Attribute\Animal;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AlmServices\Graphql\TypeContainer
 *
 * @internal
 */
class TypeContainerTest extends TestCase
{
    public function testFailsWithUnknownTypeAlias(): void
    {
        $this->expectExceptionMessage('No factory found for Foo');
        (new TypeContainer(false))->get('Foo');
    }

    public function testFailsGuessWithUnknownTypeAlias(): void
    {
        $this->expectExceptionMessage('No factory found for Foo');
        (new TypeContainer(false))->get('Foo');
    }

    /**
     * @dataProvider failingGuessesProvider
     */
    public function testFailingInvalidGuesses(string $raw, string $exceptionMessage): void
    {
        $this->expectExceptionMessage($exceptionMessage);
        (new TypeContainer(false))->guess($raw);
    }

    /**
     * @dataProvider guessDataProvider
     */
    public function testGuess(string $type, Type $expected): void
    {
        self::assertEquals($expected, (new TypeContainer(false))->guess($type));
    }

    public function testGuessObject(): void
    {
        $animalType = (new TypeContainer(false))->guess(Animal::class);
        self::assertInstanceOf(ObjectType::class, $animalType);
        self::assertEquals('AnimalAlias', $animalType->name);
    }

    public function testGuessNonNUllObject(): void
    {
        $wrapper = (new TypeContainer(false))->guess(Animal::class.'!');
        self::assertInstanceOf(NonNull::class, $wrapper);
        $animalType = $wrapper->getOfType();
        self::assertEquals('AnimalAlias', $animalType->name);
    }

    /**
     * @return iterable<string, array<string|Type>>
     */
    public function guessDataProvider(): iterable
    {
        yield 'id' => ['id', Type::id()];

        yield 'float' => ['float', Type::float()];

        yield 'string' => ['string', Type::string()];

        yield 'int' => ['int', Type::int()];

        yield 'bool' => ['bool', Type::boolean()];

        yield 'ID' => ['ID', Type::id()];

        yield 'ID!' => ['ID!', Type::nonNull(Type::id())];

        yield 'Float' => ['Float', Type::float()];

        yield 'Float!' => ['Float!', Type::nonNull(Type::float())];

        yield 'String' => ['String', Type::string()];

        yield 'String!' => ['String!', Type::nonNull(Type::string())];

        yield 'Int' => ['Int', Type::int()];

        yield 'Int!' => ['Int!', Type::nonNull(Type::int())];

        yield 'Boolean' => ['Boolean', Type::boolean()];

        yield 'Boolean!' => ['Boolean!', Type::nonNull(Type::boolean())];

        yield '[ID]!' => ['[ID]!', Type::nonNull(Type::listOf(Type::id()))];
    }

    /**
     * @return iterable<string, array<string>>
     */
    public function failingGuessesProvider(): iterable
    {
        yield '' => ['', 'No factory found for '];

        yield ' ' => [' ', 'No factory found for  '];

        yield 'Int]' => ['Int]', 'No factory found for Int]'];

        yield '[Int' => ['[Int', 'No factory found for In'];

        yield '[]' => ['[]', 'No factory found for '];

        yield '[' => ['[', 'No factory found for '];

        yield '[[Int]]' => ['[[Int]]', 'List in List found in [[Int]]'];
    }
}
