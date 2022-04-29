<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\EnumType;
use AlmServices\Graphql\Exception\ConfigurationException;
use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\Animal;
use Example\Attribute\EmptyModel;
use Example\Attribute\IdentifiableByObject;
use Example\Attribute\IdentifiableByStringifiableObject;
use Example\Attribute\InlineEnum;
use Example\Attribute\MixedResolver;
use Example\Attribute\MixedType;
use Example\Attribute\ModelWithInvalidId;
use Example\Attribute\ModelWithInvalidIdResolver;
use Example\Attribute\ModelWithInvalidList;
use Example\Attribute\NotAModel;
use Example\Attribute\NotAModelEnum;
use Example\Attribute\SelfReference;
use Example\Attribute\Untyped;
use Example\Attribute\UntypedResolver;
use GraphQL\Type\Definition\EnumValueDefinition;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.1
 *
 * @internal
 */
class ModelTest extends TestCase
{
    public function testModelName(): void
    {
        $container = new TypeContainer(false);

        /** @var ObjectType $animal */
        $animal = $container->get(Animal::class);

        self::assertInstanceOf(ObjectType::class, $animal);
        self::assertEquals('AnimalAlias', $animal->name);
        self::assertCount(6, $animal->getFields());

        self::assertEquals(['name', 'animalId', 'gangName', 'family', 'coordinates', 'isSuperior'], array_values(array_map(static fn (FieldDefinition $definition) => $definition->name, $animal->getFields())));
        self::assertEquals('String!', $animal->getField('name')->getType());
        self::assertEquals('ID!', $animal->getField('animalId')->getType());
        self::assertEquals('String', $animal->getField('gangName')->getType());
        self::assertEquals('Family!', (string) $animal->getField('family')->getType());
        self::assertEquals('[Float!]!', (string) $animal->getField('coordinates')->getType());
        self::assertEquals('Boolean!', (string) $animal->getField('isSuperior')->getType());
    }

    public function testFailsWithInvalidList(): void
    {
        $container = new TypeContainer(false);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Attribute AlmServices\Graphql\Model\ListOf cannot be attached to type of Int');
        $container->get(ModelWithInvalidList::class);
    }

    public function testFailsWithInvalidId(): void
    {
        $container = new TypeContainer(false);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('array is not valid ID');
        $container->get(ModelWithInvalidId::class);
    }

    public function testFailsWithInvalidIdResolver(): void
    {
        $container = new TypeContainer(false);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('array is not valid ID');
        $container->get(ModelWithInvalidIdResolver::class);
    }

    public function testFailsUntyped(): void
    {
        $container = new TypeContainer(false);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Missing type for bar in Example\Attribute\Untyped');
        $container->get(Untyped::class);
    }

    public function testFailsUntypedResolver(): void
    {
        $container = new TypeContainer(false);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Missing type for bar() in Example\Attribute\UntypedResolver');
        $container->get(UntypedResolver::class);
    }

    public function testFailsMixed(): void
    {
        $container = new TypeContainer(false);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Mixed type is not supported. found in bar in Example\Attribute\MixedType');
        $container->get(MixedType::class);
    }

    public function testFailsMixedResolver(): void
    {
        $container = new TypeContainer(false);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Mixed type is not supported. found in bar() in Example\Attribute\MixedResolver');
        $container->get(MixedResolver::class);
    }

    public function testSelfReference(): void
    {
        $container = new TypeContainer(false);
        $container->get(SelfReference::class);
        $this->addToAssertionCount(1);
    }

    public function testNotAModel(): void
    {
        $container = new TypeContainer(false);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Example\Attribute\NotAModel is not attributed with AlmServices\Graphql\Model\Model');
        $container->get(NotAModel::class);
    }

    /**
     * @requires PHP 8.1
     */
    public function testNotAEnum(): void
    {
        $container = new TypeContainer(false);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Example\Attribute\NotAModelEnum is not attributed with AlmServices\Graphql\Model\Model');
        $container->get(NotAModelEnum::class);
    }

    public function testEmptyModel(): void
    {
        $container = new TypeContainer(false);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Example\Attribute\EmptyModel has no properties attributed with AlmServices\Graphql\Model\Field');
        $container->get(EmptyModel::class);
    }

    public function testIdNotStringifiable(): void
    {
        $container = new TypeContainer(false);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('stdClass does not contain __toString() method');
        $container->get(IdentifiableByObject::class);
    }

    public function testIdStringifiable(): void
    {
        $container = new TypeContainer(false);
        $container->get(IdentifiableByStringifiableObject::class);
        $this->addToAssertionCount(1);
    }

    public function testInlineEnum(): void
    {
        $container = new TypeContainer(false);

        /** @var ModelType $type */
        $type = $container->get(InlineEnum::class);

        /** @var EnumType $enum */
        $enum = Type::getNullableType($type->getField('single')->getType());
        self::assertInstanceOf(\GraphQL\Type\Definition\EnumType::class, $enum);
        self::assertEquals(range('A', 'D'), array_map(static fn (EnumValueDefinition $definition) => $definition->value, $enum->getValues()));

        /** @var ListOfType $listOfType */
        $listOfType = Type::getNullableType($type->getField('list')->getType());
        self::assertInstanceOf(ListOfType::class, $listOfType);

        /** @var EnumType $enum */
        $enum = Type::getNullableType($listOfType->getWrappedType());
        self::assertEquals(range('A', 'D'), array_map(static fn (EnumValueDefinition $definition) => $definition->value, $enum->getValues()));
    }
}
