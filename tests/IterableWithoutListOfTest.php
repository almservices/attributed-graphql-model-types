<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\ModelWithIterableWithoutListOf;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AlmServices\Graphql\ModelType
 *
 * @internal
 */
class IterableWithoutListOfTest extends TestCase
{
    public function testFailure(): void
    {
        self::expectExceptionMessage('iterable not attributed with AlmServices\Graphql\Model\ListOf but is iterable found in foo');
        new ModelType(ModelWithIterableWithoutListOf::class, new TypeContainer(false), false);
    }
}
