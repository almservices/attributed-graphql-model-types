<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\ModelWithDeprecationReasonAndDescription;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.0
 * @covers \AlmServices\Graphql\ModelType
 *
 * @internal
 */
class ModelWithDeprecationReasonAndDescriptionTest extends TestCase
{
    public function test(): void
    {
        $model = new ModelType(ModelWithDeprecationReasonAndDescription::class, new TypeContainer(false), false);
        self::assertEquals(
            'Do not use ModelWithDescription.foo anymore',
            $model->getField('foo')->deprecationReason,
        );
        self::assertEquals(
            'bar',
            $model->getField('foo')->description,
        );
    }
}
