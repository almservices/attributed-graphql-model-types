<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\Model\SourceType;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.0
 * @covers \AlmServices\Graphql\Model\SourceType
 *
 * @internal
 */
class SourceTypeTest extends TestCase
{
    /**
     * @dataProvider equalsDataProvider
     */
    public function testEquals(SourceType $a, SourceType $b): void
    {
        self::assertTrue($a->equals($b));
    }

    /**
     * @dataProvider notEqualsDataProvider
     */
    public function testNotEquals(SourceType $a, SourceType $b): void
    {
        self::assertFalse($a->equals($b));
    }

    public function testConstructor(): void
    {
        SourceType::property();
        SourceType::method();
        $this->addToAssertionCount(1);
    }

    /**
     * @return iterable<array<SourceType>>
     */
    public function equalsDataProvider(): iterable
    {
        yield [SourceType::property(), SourceType::property()];

        yield [SourceType::method(), SourceType::method()];
    }

    /**
     * @return iterable<array<SourceType>>
     */
    public function notEqualsDataProvider(): iterable
    {
        yield [SourceType::method(), SourceType::property()];

        yield [SourceType::property(), SourceType::method()];
    }
}
