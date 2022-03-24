<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

interface Resolvable
{
    /**
     * @return callable|ResolverInterface
     */
    public function resolver(): callable;
}
