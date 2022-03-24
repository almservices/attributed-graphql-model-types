<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\ModelWithDescription;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \AlmServices\Graphql\ModelType
 */
class ModelWithDescriptionTest extends TestCase
{
    public function test(): void
    {
        $model = new ModelType(ModelWithDescription::class, new TypeContainer(false), false);
        self::assertEquals(
            expected: 'Bar',
            actual: $model->getField('foo')->description,
        );
    }
}
