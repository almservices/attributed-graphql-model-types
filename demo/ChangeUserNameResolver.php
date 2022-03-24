<?php

declare(strict_types=1);

namespace Demo;

use GraphQL\Type\Definition\ResolveInfo;

class ChangeUserNameResolver implements Resolver
{
    public function __invoke(mixed $root, array $args, Context $context, ResolveInfo $resolveInfo): UserModel
    {
        $user = $context->requireUser();
        $user->setFirstName($args['firstName'] ?? $user->getFirstName());
        $user->setLastName($args['lastName'] ?? $user->getLastName());

        return new UserModel($user->getId(), $user->getFirstName(), $user->getLastName());
    }
}
