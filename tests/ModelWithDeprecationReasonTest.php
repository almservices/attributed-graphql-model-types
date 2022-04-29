<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\ModelWithDeprecationReason;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.0
 *
 * @internal
 */
class ModelWithDeprecationReasonTest extends TestCase
{
    public function test(): void
    {
        $model = new ModelType(ModelWithDeprecationReason::class, new TypeContainer(false), false);
        self::assertEquals(
            'Do not use ModelWithDescription.foo anymore',
            $model->getField('foo')->deprecationReason,
        );
    }
}
