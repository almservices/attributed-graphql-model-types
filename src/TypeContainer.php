<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

use AlmServices\Graphql\Exception\ConfigurationException;
use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\Type;

class TypeContainer
{
    /** @var array<string, (NullableType|Type|(\Closure(): (NullableType|Type)))> */
    private array $types = [];
    private bool $async;

    public function __construct(bool $async = true)
    {
        $this->async = $async;

        // PHP
        $this->set('id', Type::id());
        $this->set('float', Type::float());
        $this->set('string', Type::string());
        $this->set('int', Type::int());
        $this->set('bool', Type::boolean());

        // Graphql
        $this->set('ID', Type::id());
        $this->set('Float', Type::float());
        $this->set('String', Type::string());
        $this->set('Int', Type::int());
        $this->set('Boolean', Type::boolean());
    }

    /**
     * @param class-string|string $name
     * @param NullableType|Type|(\Closure(): (NullableType|Type)) $type
     */
    public function set(string $name, $type): void
    {
        if (isset($this->types[$name])) {
            return;
        }

        $this->types[$name] = $type;
    }

    /**
     * @param class-string|string $name
     *
     * @return NullableType|Type|(\Closure(): (NullableType|Type))
     */
    public function get(string $name)
    {
        if (!isset($this->types[$name])) {
            if (!class_exists($name)) {
                throw new ConfigurationException('No factory found for '.$name);
            }

            if (interface_exists(\UnitEnum::class) && (new \ReflectionClass($name))->implementsInterface(\UnitEnum::class)) {
                $factory = fn () => new EnumType($name, $this);
            } else {
                $factory = fn () => new ModelType($name, $this, $this->async);
            }
            $this->types[$name] = $this->async ? fn () => $this->promise($name, $factory) : $factory();
        }

        return $this->types[$name];
    }

    /**
     * @return NullableType|Type|(\Closure(): (NullableType|Type))
     */
    public function guess(string $rawType)
    {
        if (str_starts_with($rawType, '[')) {
            $listTypeClosingPosition = strpos($rawType, ']');

            if (false === $listTypeClosingPosition) {
                throw new ConfigurationException('List not closed in '.$rawType);
            }

            $subTypeRaw = substr($rawType, 1, $listTypeClosingPosition - 1);
            if (str_contains($subTypeRaw, '[') || str_contains($subTypeRaw, ']')) {
                throw new ConfigurationException('List in List found in '.$rawType);
            }
            $type = ObjectType::listOf($this->guess($subTypeRaw));
            if (str_ends_with($rawType, '!')) {
                $type = Type::nonNull($type);
            }

            return $type;
        }

        $isNonNull = str_ends_with($rawType, '!');
        if ($isNonNull) {
            $rawType = substr($rawType, 0, -1);
        }
        $type = $this->get($rawType);

        if ($isNonNull && (is_callable($type) || $type instanceof NullableType)) {
            $type = Type::nonNull($type);
        }

        return $type;
    }

    /**
     * @param class-string $klass
     */
    private function promise(string $klass, \Closure $factory): Type
    {
        if ($this->types[$klass] instanceof Type) {
            return $this->types[$klass];
        }

        $this->types[$klass] = $factory();

        return $this->types[$klass];
    }
}
