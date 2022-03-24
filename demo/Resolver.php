<?php

declare(strict_types=1);

namespace Demo;

use GraphQL\Type\Definition\ResolveInfo;

interface Resolver
{
    public function __invoke(mixed $root, array $args, Context $context, ResolveInfo $resolveInfo): mixed;
}
