<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\DescriptionModel;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.0
 *
 * @internal
 */
class DescriptionModelTest extends TestCase
{
    public function test(): void
    {
        $model = new ModelType(DescriptionModel::class, new TypeContainer(false), false);
        self::assertEquals(
            'Authorization required',
            $model->description,
        );
    }
}
