<?php

declare(strict_types=1);

namespace Demo;

use GraphQL\Type\Definition\ResolveInfo;

/**
 * @template-extends UserModel
 */
class MyUserResolver implements Resolver
{
    public function __invoke(
        mixed $root,
        array $args,
        Context $context,
        ResolveInfo $resolveInfo
    ): UserModel {
        $user = $context->requireUser();

        return new UserModel(
            id: $user->getId(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName()
        );
    }
}
