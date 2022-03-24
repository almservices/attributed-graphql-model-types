<?php

declare(strict_types=1);

namespace Demo;

use GraphQL\Error\Error;

class Context implements \AlmServices\Graphql\Context
{
    private readonly \Closure $userProvider;

    private bool $isUserSet = false;
    private ?User $user;

    public function __construct(callable $userProvider)
    {
        $this->userProvider = $userProvider(...);
    }

    public function user(): ?User
    {
        if (!$this->isUserSet) {
            $this->user = ($this->userProvider)();
        }

        return $this->user;
    }

    public function requireUser(): User
    {
        $user = $this->user();

        if (null === $user) {
            throw new Error('Access Denied', extensions: ['category' => 'auth']);
        }

        return $user;
    }
}
