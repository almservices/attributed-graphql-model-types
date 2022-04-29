<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\TypeContainer;
use Example\Attribute\EnumClash;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.1
 *
 * @internal
 */
class EnumClashTest extends TestCase
{
    public function testFailure(): void
    {
        $typeContainer = new TypeContainer(false);
        $this->expectExceptionMessage('Duplicated name FOO in Example\Attribute\EnumClash');
        $typeContainer->get(EnumClash::class);
    }
}
