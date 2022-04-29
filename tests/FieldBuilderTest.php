<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\Context;
use AlmServices\Graphql\Field;
use AlmServices\Graphql\FieldBuilder;
use AlmServices\Graphql\FieldBuilderStrategy;
use AlmServices\Graphql\FieldDefaultValue;
use AlmServices\Graphql\FieldDeprecated;
use AlmServices\Graphql\FieldDescribable;
use AlmServices\Graphql\FieldInterface;
use AlmServices\Graphql\FieldMapping;
use AlmServices\Graphql\FieldResolvable;
use AlmServices\Graphql\Resolvable;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.0
 *
 * @internal
 * @covers \AlmServices\Graphql\FieldBuilder
 */
class FieldBuilderTest extends TestCase
{
    /** @var FieldBuilder<string> */
    private FieldBuilder $fieldBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fieldBuilder = new FieldBuilder('foo', Type::nonNull(Type::string()));
        $this->fieldBuilder->setStrategy(FieldBuilderStrategy::virtual());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->fieldBuilder);
    }

    public function testBasic(): void
    {
        $result = $this->fieldBuilder->build();
        $reflection = new \ReflectionClass($result);

        // constructor, name, type
        self::assertCount(3, $reflection->getMethods());
        self::assertInstanceOf(FieldInterface::class, $result);
        self::assertInstanceOf(Field::class, $result);
        self::assertEquals('foo', $result->name());
        self::assertEquals(Type::nonNull(Type::string()), $result->type());
    }

    public function testDescription(): void
    {
        $result = $this->fieldBuilder
            ->setDescription('bar')
            ->build()
        ;
        $reflection = new \ReflectionClass($result);

        // constructor, name, type, description
        self::assertCount(4, $reflection->getMethods());
        self::assertInstanceOf(FieldInterface::class, $result);
        self::assertInstanceOf(FieldDescribable::class, $result);

        /** @var FieldDescribable&FieldInterface $result */
        self::assertEquals('foo', $result->name());
        self::assertEquals(Type::nonNull(Type::string()), $result->type());
        self::assertEquals('bar', $result->description());
    }

    public function testDeprecationReason(): void
    {
        $result = $this->fieldBuilder
            ->setDeprecationReason('bar')
            ->build()
        ;
        $reflection = new \ReflectionClass($result);

        // constructor, name, type, deprecationReason
        self::assertCount(4, $reflection->getMethods());
        self::assertInstanceOf(FieldInterface::class, $result);
        self::assertInstanceOf(FieldDeprecated::class, $result);

        /** @var FieldDeprecated&FieldInterface $result */
        self::assertEquals('foo', $result->name());
        self::assertEquals(Type::nonNull(Type::string()), $result->type());
        self::assertEquals('bar', $result->deprecationReason());
    }

    public function testDefaultValue(): void
    {
        $result = $this->fieldBuilder
            ->setDefaultValue('bar')
            ->build()
        ;
        $reflection = new \ReflectionClass($result);

        // constructor, name, type, defaultValue
        self::assertCount(4, $reflection->getMethods());
        self::assertInstanceOf(FieldInterface::class, $result);
        self::assertInstanceOf(FieldDefaultValue::class, $result);

        /** @var FieldDefaultValue&FieldInterface $result */
        self::assertEquals('foo', $result->name());
        self::assertEquals(Type::nonNull(Type::string()), $result->type());
        self::assertEquals('bar', $result->defaultValue());
    }

    public function testMapping(): void
    {
        $result = $this->fieldBuilder
            ->setMapping(static fn (string $arg) => strtoupper($arg))
            ->build()
        ;
        $reflection = new \ReflectionClass($result);

        // constructor, name, type, map
        self::assertCount(4, $reflection->getMethods());
        self::assertInstanceOf(FieldInterface::class, $result);
        self::assertInstanceOf(FieldMapping::class, $result);

        /** @var FieldInterface&FieldMapping $result */
        self::assertEquals('foo', $result->name());
        self::assertEquals(Type::nonNull(Type::string()), $result->type());
        self::assertEquals('BAR', $result->map('bar'));
    }

    public function testResolver(): void
    {
        $result = $this->fieldBuilder
            ->setResolver(static fn () => 'bar')
            ->build()
        ;
        $reflection = new \ReflectionClass($result);

        // constructor, name, type, resolver
        self::assertCount(4, $reflection->getMethods());
        self::assertInstanceOf(FieldInterface::class, $result);
        self::assertInstanceOf(Resolvable::class, $result);

        // one of most used ones, no goal to create it
        self::assertInstanceOf(FieldResolvable::class, $result);

        /** @var FieldInterface&FieldResolvable&Resolvable $result */
        self::assertEquals('foo', $result->name());
        self::assertEquals(Type::nonNull(Type::string()), $result->type());
        self::assertEquals('bar', $result->resolver()(
            null,
            [],
            $this->getMockBuilder(Context::class)->getMock(),
            $this->getMockBuilder(ResolveInfo::class)->disableOriginalConstructor()->getMock()
        ));
    }
}
