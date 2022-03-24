<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\Type;

class FieldArgumentable extends Field implements Argumentable, Resolvable
{
    private Arguments $args;

    /** @var callable|ResolverInterface */
    private $resolver;

    /**
     * @param NullableType|Type|(\Closure(): (NullableType|Type)) $type
     * @param callable|ResolverInterface $resolver
     */
    public function __construct(string $name, $type, $resolver, Arguments $args)
    {
        parent::__construct($name, $type);
        $this->args = $args;
        $this->resolver = $resolver;
    }

    public function args(): Arguments
    {
        return $this->args;
    }

    /**
     * @return callable|ResolverInterface
     */
    public function resolver(): callable
    {
        return $this->resolver;
    }
}
