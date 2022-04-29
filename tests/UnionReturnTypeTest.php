<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\ModelWithUnion;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.1
 *
 * @covers \AlmServices\Graphql\ModelType
 *
 * @internal
 */
class UnionReturnTypeTest extends TestCase
{
    public function testFailure(): void
    {
        self::expectExceptionMessage('Intersection and union types are not supported. found in foo in Example\Attribute\ModelWithUnion');
        new ModelType(ModelWithUnion::class, new TypeContainer(false), false);
    }
}
