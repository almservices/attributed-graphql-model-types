<?php

declare(strict_types=1);

namespace Example\Annotation;

use AlmServices\Graphql\Model as GQL;

/**
 * @GQL\Model(name="BasicModel")
 */
class BasicModel
{
    /**
     * @GQL\ID
     * @GQL\Field
     */
    public int $id;

    /**
     * @GQL\Field
     * @GQL\Alias("nameAlias")
     */
    public string $name;

    /**
     * @GQL\Field
     * @GQL\Enum("State", {"A", "B", "C"})
     */
    public string $state;

    public function __construct(int $id, string $name, string $state)
    {
        $this->id = $id;
        $this->name = $name;
        $this->state = $state;
    }

    /**
     * @GQL\Field
     * @GQL\Argument("foo", "String!")
     */
    public function resolver(array $args): string
    {
        return $args['foo'];
    }
}
