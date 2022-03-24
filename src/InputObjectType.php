<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

use GraphQL\Type\Definition\InputObjectType as BaseObjectType;

class InputObjectType extends BaseObjectType
{
    use FieldFactory {
        create as createFields;
    }

    /**
     * @param (\Closure(): iterable<FieldInterface>[])|FieldInterface[]|iterable<FieldInterface> $fields
     */
    public function __construct(string $name, $fields)
    {
        parent::__construct([
            'name' => $name,
            'fields' => self::createFields($fields),
        ]);
    }
}
