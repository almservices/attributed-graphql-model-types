<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Alias;
use AlmServices\Graphql\Model\Description;
use AlmServices\Graphql\Model\Ignore;
use AlmServices\Graphql\Model\Model;

#[Model(name: 'Family')]
enum Family
{
    #[Alias('seal')]
    case SEAL;
    #[Description('Furry animal it is')]
    case BEAR;
    #[Ignore]
    case PUSSY_CAT;
}
