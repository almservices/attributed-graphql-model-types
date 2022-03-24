<?php

declare(strict_types=1);

namespace Demo;

use AlmServices\Graphql\QueryInterface;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

class MyUserQuery implements QueryInterface
{
    private readonly \Closure|Type $userType;
    private readonly MyUserResolver $resolver;

    public function __construct(
        \Closure|Type $userType,
        MyUserResolver $resolver
    ) {
        $this->userType = $userType;
        $this->resolver = $resolver;
    }

    public function name(): string
    {
        return 'myUser';
    }

    public function type(): NonNull
    {
        return Type::nonNull($this->userType);
    }

    public function resolver(): callable
    {
        return $this->resolver;
    }
}
