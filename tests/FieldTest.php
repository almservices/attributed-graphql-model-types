<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\Field;
use GraphQL\Type\Definition\Type;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.0
 * @covers \AlmServices\Graphql\Field
 *
 * @internal
 */
class FieldTest extends TestCase
{
    private Field $field;

    protected function setUp(): void
    {
        parent::setUp();
        $this->field = new Field('name', static fn () => Type::string());
    }

    public function testField(): void
    {
        self::assertEquals($this->field->name(), 'name');
        self::assertIsCallable($this->field->type());
    }
}
