<?php

declare(strict_types=1);

namespace Example;

use AlmServices\Graphql\Context as ContextInterface;

class Context implements ContextInterface
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function username(): string
    {
        return $this->username;
    }
}
