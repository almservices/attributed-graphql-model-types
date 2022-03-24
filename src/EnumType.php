<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

use AlmServices\Graphql\Exception\ConfigurationException;
use AlmServices\Graphql\Model\Alias;
use AlmServices\Graphql\Model\Description;
use AlmServices\Graphql\Model\Ignore;
use AlmServices\Graphql\Model\Model;
use GraphQL\Type\Definition\EnumType as BaseEnumType;

class EnumType extends BaseEnumType
{
    public function __construct(string $klass, TypeContainer $container)
    {
        if (\PHP_VERSION_ID <= 80000) {
            throw new \RuntimeException('There are no enums in php '.\PHP_VERSION_ID);
        }

        /**
         * @var array<string, array<string, null|string|\UnitEnum>> $mapping
         */
        $mapping = [];

        $reflection = new \ReflectionEnum($klass);

        $modelAttr = $reflection->getAttributes(Model::class);

        if (empty($modelAttr)) {
            throw new ConfigurationException("{$klass} is not attributed with ".Model::class);
        }

        /** @var Model $modelAttr */
        $modelAttr = $modelAttr[0]->newInstance();
        $modelName = $modelAttr->name;
        $container->set($klass, $this);
        $container->set($modelName, $this);

        $publicConstants = $reflection->getConstants(\ReflectionClassConstant::IS_PUBLIC);

        /**
         * @var string $constName
         */
        foreach ($publicConstants as $constName => $value) {
            /** @var \ReflectionClassConstant $constReflection */
            $constReflection = $reflection->getReflectionConstant($constName);

            if ($constReflection->getAttributes(Ignore::class)) {
                continue;
            }

            $attributes = $constReflection->getAttributes(Alias::class);
            $alias = null;
            if (!empty($attributes)) {
                $alias = $attributes[0]->newInstance();
            }

            $description = null;
            $descriptionAttributes = $constReflection->getAttributes(Description::class);
            if (!empty($descriptionAttributes)) {
                $description = $descriptionAttributes[0]->newInstance()->description;
            }

            /** @var null|Alias $alias */
            $name = $alias?->name ?? $constReflection->name;
            if (key_exists($name, $mapping)) {
                throw new ConfigurationException('Duplicated name '.$name.' in '.$klass);
            }
            $mapping[$name] = [
                'name' => $name,
                'value' => $reflection->getCase($constName)->getValue(),
                'description' => $description,
            ];
        }

        parent::__construct([
            'name' => $modelName,
            'values' => $mapping,
        ]);
    }
}
