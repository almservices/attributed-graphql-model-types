<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\DescriptionModel;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AlmServices\Graphql\ModelType
 *
 * @internal
 */
class DescriptionModelTest extends TestCase
{
    public function test(): void
    {
        $model = new ModelType(DescriptionModel::class, new TypeContainer(false), false);
        self::assertEquals(
            expected: 'Authorization required',
            actual: $model->description,
        );
    }
}
