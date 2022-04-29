<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\ModelWithIntersection;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.0
 *
 * @internal
 */
class IntersectionReturnTypeTest extends TestCase
{
    public function testFailure(): void
    {
        self::expectExceptionMessage('Intersection and union types are not supported. found in foo in Example\Attribute\ModelWithIntersection');
        new ModelType(ModelWithIntersection::class, new TypeContainer(false), false);
    }
}
