<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\Type;

class FieldResolvable extends Field implements Resolvable
{
    /** @var callable|ResolverInterface */
    private $resolver;

    /**
     * @param NullableType|Type|(\Closure(): (NullableType|Type)) $type
     * @param callable|ResolverInterface $resolver
     */
    public function __construct(string $name, $type, $resolver)
    {
        parent::__construct($name, $type);
        $this->resolver = $resolver;
    }

    /**
     * {@inheritDoc}
     */
    public function resolver(): callable
    {
        return $this->resolver;
    }
}
