<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\ObjectType as BaseObjectType;
use GraphQL\Type\Definition\Type;

class ObjectType extends BaseObjectType
{
    use FieldFactory {
        create as createFields;
    }

    /**
     * @param FieldInterface[]|\Closure(): FieldInterface[] $fields
     * @param FieldInterface[]|\Closure(): FieldInterface[] $args
     */
    public function __construct(
        string $name,
        $fields,
        $args = [],
        ?callable $resolver = null,
        ?string $description = null
    ) {
        parent::__construct([
            'name' => $name,
            'args' => static function () use ($args) {
                $argMap = [];

                $args = is_callable($args) ? $args() : $args;
                foreach ($args as $arg) {
                    $argMap[$arg->name()] = $arg->type();
                }

                return $argMap;
            },
            'fields' => self::createFields($fields),
            'resolver' => $resolver,
            'description' => $description,
        ]);
    }

    /**
     * It's messy for older version of webonyx graphql.
     *
     * @param NullableType|Type|(\Closure(): (NullableType|Type)) $wrappedType
     */
    public static function listOf($wrappedType): ListOfType
    {
        if (is_callable($wrappedType)) {
            $reflectionMethod = new \ReflectionMethod(BaseObjectType::class);
            $parameters = $reflectionMethod->getParameters();
            $firstParam = $parameters[0];
            if ($firstParam->getType() instanceof \ReflectionNamedType) {
                /** @var Type $resolvedWrappedType */
                $resolvedWrappedType = $wrappedType();

                return parent::listOf($resolvedWrappedType);
            }
        }

        return parent::listOf($wrappedType);
    }
}
