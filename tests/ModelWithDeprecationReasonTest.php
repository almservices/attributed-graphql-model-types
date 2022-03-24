<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\ModelWithDeprecationReason;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \AlmServices\Graphql\ModelType
 */
class ModelWithDeprecationReasonTest extends TestCase
{
    public function test(): void
    {
        $model = new ModelType(ModelWithDeprecationReason::class, new TypeContainer(false), false);
        self::assertEquals(
            expected: 'Do not use ModelWithDescription.foo anymore',
            actual: $model->getField('foo')->deprecationReason,
        );
    }
}
