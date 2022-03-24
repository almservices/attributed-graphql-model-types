<?php

declare(strict_types=1);

namespace Demo;

use AlmServices\Graphql\ModelType;
use AlmServices\Graphql\TypeContainer;

class UserModelType extends ModelType
{
    public function __construct(TypeContainer $container)
    {
        parent::__construct(UserModel::class, $container);
    }
}
