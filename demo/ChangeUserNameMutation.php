<?php

declare(strict_types=1);

namespace Demo;

use AlmServices\Graphql\Argumentable;
use AlmServices\Graphql\Arguments;
use AlmServices\Graphql\Field;
use AlmServices\Graphql\MutationInterface;
use GraphQL\Type\Definition\Type;

class ChangeUserNameMutation implements MutationInterface, Argumentable
{
    private readonly \Closure|Type $userType;
    private readonly ChangeUserNameResolver $resolver;

    public function __construct(
        Type|\Closure $userType,
        ChangeUserNameResolver $resolver
    ) {
        $this->userType = $userType;
        $this->resolver = $resolver;
    }

    public function args(): Arguments
    {
        return new Arguments(
            new Field('firstName', Type::string()),
            new Field('lastName', Type::string()),
        );
    }

    public function name(): string
    {
        return 'changeUserName';
    }

    public function type(): Type|callable
    {
        return Type::nonNull($this->userType);
    }

    public function resolver(): callable
    {
        return $this->resolver;
    }
}
