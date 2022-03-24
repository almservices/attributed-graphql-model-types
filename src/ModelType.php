<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

use AlmServices\Graphql\Exception\ConfigurationException;
use AlmServices\Graphql\Model\Alias;
use AlmServices\Graphql\Model\Argument;
use AlmServices\Graphql\Model\Deprecated;
use AlmServices\Graphql\Model\Description;
use AlmServices\Graphql\Model\Enum;
use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ID;
use AlmServices\Graphql\Model\ListOf;
use AlmServices\Graphql\Model\Model;
use AlmServices\Graphql\Model\NonNull;
use AlmServices\Graphql\Model\Source;
use AlmServices\Graphql\Model\SourceType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\Type;

class ModelType extends ObjectType
{
    /**
     * @template T of object
     *
     * @param class-string<T> $klass
     */
    public function __construct(string $klass, TypeContainer $container, bool $async = true)
    {
        $reflection = new \ReflectionClass($klass);
        $modelAttr = AttributeResolver::getClassAttribute($reflection, Model::class);

        if (!$modelAttr) {
            throw new ConfigurationException("{$klass} is not attributed with ".Model::class);
        }

        $container->set($klass, $this);
        $name = $modelAttr->name;
        $container->set($name, $this);

        /** @var \Closure(): \Generator<FieldInterface> $fields */
        $fields = function () use ($reflection, $klass, $container): \Generator {
            $i = 0;

            $sources = $this->collectSources($reflection);

            foreach ($sources as $source) {
                /** @var null|Field $field */
                $field = $source->getAttribute(Field::class);
                if (null === $field) {
                    continue;
                }

                /** @var null|ListOf $listOf */
                $listOf = $source->getAttribute(ListOf::class);

                /** @var null|NonNull $nonNull */
                $nonNull = $source->getAttribute(NonNull::class);

                $returnType = $source->reflectionType();
                $this->validateReturnType($source, $returnType, $klass);

                /** @var \ReflectionNamedType $returnType */
                $isId = (bool) $source->getAttribute(ID::class);

                /** @var null|Enum $enum */
                $enum = $source->getAttribute(Enum::class);
                if ($enum) {
                    $type = new \GraphQL\Type\Definition\EnumType([
                        'name' => $enum->name,
                        'values' => $enum->values,
                    ]);
                    $container->set($enum->name, $type);
                }

                $typeName = $returnType->getName();
                if (!$isId && $this->isIterable($typeName)) {
                    if (null === $listOf) {
                        throw new ConfigurationException($typeName.' not attributed with '.ListOf::class.' but is iterable found in '.$source->debugName());
                    }

                    $type = self::listOf(
                        $this->tryInferType($listOf->type, $container, $nonNull)
                    );
                } else {
                    $type = $this->tryInferType($isId ? 'id' : $typeName, $container, $nonNull);
                }

                if ($enum && !$listOf) {
                    $type = $container->get($enum->name);
                }

                if ($listOf) {
                    $this->validateList($type);
                }

                if ($isId) {
                    $this->validateId($typeName);
                }

                if (!$returnType->allowsNull() && (is_callable($type) || $type instanceof NullableType)) {
                    $type = self::nonNull($type);
                }

                /** @var null|Alias $alias */
                $alias = $source->getAttribute(Alias::class);

                /** @var null|Deprecated $deprecated */
                $deprecated = $source->getAttribute(Deprecated::class);

                /** @var null|Description $description */
                $description = $source->getAttribute(Description::class);

                ++$i;
                $modelName = isset($alias) ? $alias->name : $source->name();
                $fieldBuilder = (new FieldBuilder($modelName, $type))
                    ->setResolver($source->resolver())
                ;
                if ($deprecated) {
                    $fieldBuilder->setDeprecationReason($deprecated->reason);
                }
                if ($description) {
                    $fieldBuilder->setDescription($description->description);
                }

                /** @var Argument $argument */
                foreach ($source->getAttributes(Argument::class) as $argument) {
                    $argType = $container->guess($argument->type);
                    if (!$argument->nullable && (is_callable($argType) || $argType instanceof NullableType)) {
                        $argType = self::nonNull($argType);
                    }
                    $fieldBuilder->addArgument(new \AlmServices\Graphql\Field($argument->name, $argType));
                }

                yield $fieldBuilder->build();
            }

            if (0 === $i) {
                $this->tipDeveloperOnFieldsErrors($klass, $sources);
            }
        };

        if (!$async) {
            $fields = iterator_to_array($fields());
        }

        $description = null;
        $descriptionAttr = AttributeResolver::getClassAttribute($reflection, Description::class);
        if ($descriptionAttr) {
            $description = $descriptionAttr->description;
        }

        parent::__construct(
            $name,
            $fields,
            [],
            null,
            $description
        );
    }

    /**
     * @return NullableType|Type|(\Closure(): (NullableType|Type)) $type
     */
    private function tryInferType(string $type, TypeContainer $container, ?NonNull $nonNull)
    {
        $type = $container->get($type);

        if ($nonNull && (is_callable($type) || $type instanceof NullableType)) {
            $type = self::nonNull($type);
        }

        return $type;
    }

    /**
     * @param NullableType|Type|(\Closure(): (NullableType|Type)) $type
     */
    private function validateList($type): void
    {
        if (is_callable($type)) {
            return;
        }

        if ($type instanceof ListOfType) {
            return;
        }

        /** @var Type $type */
        throw new ConfigurationException(sprintf('Attribute %s cannot be attached to type of %s', ListOf::class, $type->name));
    }

    private function validateId(string $typeName): void
    {
        if (in_array($typeName, ['int', 'string'], true)) {
            return;
        }

        if (!class_exists($typeName)) {
            throw new ConfigurationException("{$typeName} is not valid ID");
        }

        $reflection = new \ReflectionClass($typeName);
        if ($reflection->hasMethod('__toString')) {
            return;
        }

        throw new ConfigurationException("{$typeName} does not contain __toString() method");
    }

    private function isIterable(string $typeName): bool
    {
        $interfaces = [\Traversable::class, \ArrayAccess::class];
        if (in_array($typeName, ['iterable', 'array'] + $interfaces)) {
            return true;
        }

        if (!class_exists($typeName) && !interface_exists($typeName)) {
            return false;
        }
        $reflection = new \ReflectionClass($typeName);
        foreach ($interfaces as $interface) {
            if ($reflection->implementsInterface($interface)) {
                return true;
            }
        }

        return $reflection->isIterable();
    }

    /**
     * @template T of object
     *
     * @param \ReflectionClass<T> $reflection
     *
     * @return array<Source>
     */
    private function collectSources(\ReflectionClass $reflection): array
    {
        /** @var array<Source> $sources */
        $sources = [];

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $sources[] = new Source($method->name, static fn ($root, $args, $context, $resolveInfo) => $root->{$method->name}($args, $context, $resolveInfo), $method->getReturnType(), AttributeResolver::getMethodAttributes($method), SourceType::method());
        }

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $sources[] = new Source(
                $property->name,
                static fn ($root) => $root->{$property->name},
                $property->getType(),
                AttributeResolver::getPropertyAttributes($property),
                SourceType::property()
            );
        }

        return $sources;
    }

    private function validateReturnType(Source $source, ?\ReflectionType $returnType, string $klass): void
    {
        if (null === $returnType) {
            throw new ConfigurationException('Missing type for '.$source->debugName().' in '.$klass);
        }

        if (!$returnType instanceof \ReflectionNamedType) {
            throw new ConfigurationException('Intersection and union types are not supported. found in '.$source->debugName().' in '.$klass);
        }

        if ('mixed' === $returnType->getName()) {
            throw new ConfigurationException('Mixed type is not supported. found in '.$source->debugName().' in '.$klass);
        }
    }

    /**
     * @param array<Source> $sources
     */
    private function tipDeveloperOnFieldsErrors(string $klass, array $sources): void
    {
        $possibilities = [];
        $relevantAttributes = [
            ListOf::class,
            NonNull::class,
            ID::class,
            Alias::class,
        ];
        foreach ($sources as $source) {
            foreach ($relevantAttributes as $relevantAttribute) {
                if (null !== $source->getAttribute($relevantAttribute)) {
                    $possibilities[] = 'probably '.$source->debugName().' should be as it is attributed with '.$relevantAttribute;
                }
            }
        }

        throw new ConfigurationException($klass.' has no properties attributed with '.Field::class.' '.implode(', ', $possibilities));
    }
}
