<?php

namespace AlmServices\Graphql;

use GraphQL\Type\Definition\ResolveInfo;

/**
 * @template T
 */
interface ResolverInterface
{
    /**
     * @param mixed                $root
     * @param array<string, mixed> $args
     *
     * @return T
     */
    public function __invoke($root, array $args, Context $context, ResolveInfo $resolveInfo);
}
