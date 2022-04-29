<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\FieldBuilderStrategy;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.0
 *
 * @internal
 * @coversNothing
 */
class FieldBuilderStrategyTest extends TestCase
{
    /**
     * @dataProvider allDataProvider
     */
    public function testIsItself(FieldBuilderStrategy $strategy): void
    {
        self::assertTrue($strategy->is($strategy));
    }

    /**
     * @dataProvider allDataProvider
     */
    public function testEqualsItself(FieldBuilderStrategy $strategy): void
    {
        self::assertTrue($strategy->equals($strategy));
    }

    public function testFileIsNotVirtual(): void
    {
        self::assertFalse(FieldBuilderStrategy::file()->is(FieldBuilderStrategy::virtual()));
    }

    public function testVirtualIsNotFile(): void
    {
        self::assertFalse(FieldBuilderStrategy::virtual()->is(FieldBuilderStrategy::file()));
    }

    public function testHybrid(): void
    {
        self::assertTrue(FieldBuilderStrategy::hybrid()->is(FieldBuilderStrategy::file()));
        self::assertTrue(FieldBuilderStrategy::hybrid()->is(FieldBuilderStrategy::virtual()));
    }

    /**
     * @return \Generator<string, array<FieldBuilderStrategy>>
     */
    public function allDataProvider(): \Generator
    {
        yield 'virtual' => [FieldBuilderStrategy::virtual()];

        yield 'file' => [FieldBuilderStrategy::file()];

        yield 'hybrid' => [FieldBuilderStrategy::hybrid()];
    }
}
