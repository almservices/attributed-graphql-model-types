<?php

declare(strict_types=1);

namespace Demo;

use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ID;
use AlmServices\Graphql\Model\Model;

#[Model('User')]
class UserModel
{
    #[ID]
    #[Field]
    public readonly int $id;

    #[Field]
    public readonly string $firstName;

    #[Field]
    public readonly string $lastName;

    public function __construct(int $id, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}
