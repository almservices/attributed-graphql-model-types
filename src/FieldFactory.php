<?php

namespace AlmServices\Graphql;

use AlmServices\Graphql\Exception\ConfigurationException;

trait FieldFactory
{
    /**
     * @param (\Closure(): iterable<FieldInterface>[])|FieldInterface[]|iterable<FieldInterface> $fields
     */
    protected static function create($fields): callable
    {
        return static function () use ($fields) {
            $fieldsMap = [];

            $fields = is_callable($fields) ? $fields() : $fields;

            /** @var FieldInterface $field */
            foreach ($fields as $field) {
                if (isset($fieldsMap[$field->name()])) {
                    throw new ConfigurationException(sprintf("Duplicated Field '%s'", $field->name()));
                }

                $fieldsMap[$field->name()] = [
                    'type' => $field->type(),
                ];

                if ($field instanceof Argumentable) {
                    $fieldsMap[$field->name()]['args'] = self::create($field->args()->fields())();
                }

                if ($field instanceof FieldDescribable) {
                    $fieldsMap[$field->name()]['description'] = $field->description();
                }

                if ($field instanceof FieldDeprecated) {
                    $fieldsMap[$field->name()]['deprecationReason'] = $field->deprecationReason();
                }

                if ($field instanceof Resolvable) {
                    $fieldsMap[$field->name()]['resolve'] = static function ($root, array $args, ...$rest) use ($field) {
                        $mappedArguments = [];
                        if ($field instanceof Argumentable) {
                            foreach ($field->args()->fields() as $arg) {
                                $mappedArguments[$arg->name()] = $args[$arg->name()] ?? null;
                                if ($arg instanceof FieldMapping) {
                                    /** @var FieldInterface&FieldMapping $arg */
                                    $mappedArguments[$arg->name()] = $arg->map($mappedArguments[$arg->name()]);
                                }
                            }
                        }

                        return $field->resolver()($root, $mappedArguments, ...$rest);
                    };
                }

                if ($field instanceof FieldDefaultValue) {
                    $fieldsMap[$field->name()]['defaultValue'] = $field->defaultValue();
                }
            }

            return $fieldsMap;
        };
    }
}
