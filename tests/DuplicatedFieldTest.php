<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\ObjectType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\DuplicatedFieldModel;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.0
 *
 * @internal
 * @covers \FieldFactory
 */
class DuplicatedFieldTest extends TestCase
{
    public function testFailure(): void
    {
        $this->expectExceptionMessage('Duplicated Field \'foo\'');

        /** @var ObjectType $type */
        $type = (new TypeContainer(false))->get(DuplicatedFieldModel::class);
        $type->getFields();
    }
}
