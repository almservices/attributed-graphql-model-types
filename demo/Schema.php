<?php

declare(strict_types=1);

namespace Demo;

class Schema extends \GraphQL\Type\Schema
{
    public function __construct(Query $query, Mutation $mutation)
    {
        parent::__construct([
            'query' => $query,
            'mutation' => $mutation,
        ]);
    }
}
