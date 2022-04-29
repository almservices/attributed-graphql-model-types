<?php

declare(strict_types=1);

namespace AlmServices\Test;

use AlmServices\Graphql\Exception\ConfigurationException;
use AlmServices\Graphql\Model\Alias;
use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ID;
use AlmServices\Graphql\Model\ListOf;
use AlmServices\Graphql\Model\NonNull;
use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;
use Example\Attribute\ModelWithoutFields;
use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 8.0
 *
 * @internal
 */
class ModelWithoutFieldsTest extends TestCase
{
    public function testFailure(): void
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage(
            ModelWithoutFields::class.' has no properties attributed with '.Field::class
            .' probably id should be as it is attributed with '.ID::class
            .', probably foo should be as it is attributed with '.ListOf::class
            .', probably bar should be as it is attributed with '.NonNull::class
            .', probably baz should be as it is attributed with '.Alias::class
        );
        new ModelType(ModelWithoutFields::class, new TypeContainer(false), false);
    }
}
