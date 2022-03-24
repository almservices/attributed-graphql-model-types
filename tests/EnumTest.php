<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\TypeContainer;
use Example\Attribute\Family;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\EnumValueDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AlmServices\Graphql\EnumType
 *
 * @internal
 */
class EnumTest extends TestCase
{
    public function testEnumValues(): void
    {
        $typeContainer = new TypeContainer(false);

        /** @var EnumType $enumType */
        $enumType = $typeContainer->get(Family::class);

        self::assertInstanceOf(EnumType::class, $enumType);
        self::assertEquals(
            ['seal', 'BEAR'],
            array_map(static fn (EnumValueDefinition $enumValueDefinition) => $enumValueDefinition->name, $enumType->getValues())
        );
        self::assertEquals(
            [Family::SEAL, Family::BEAR],
            array_map(static fn (EnumValueDefinition $enumValueDefinition) => $enumValueDefinition->value, $enumType->getValues())
        );

        $sealType = $enumType->getValue('seal');
        self::assertNotNull($sealType);
        self::assertNull($sealType->description);

        $bearType = $enumType->getValue('BEAR');
        self::assertNotNull($bearType);
        self::assertEquals(
            'Furry animal it is',
            $bearType->description
        );
    }
}
