<?php

declare(strict_types=1);

namespace AlmServices\Graphql\Model;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @NamedArgumentConstructor
 * @Annotation
 */
#[\Attribute(flags: \Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS_CONSTANT)]
class Alias
{
    public string $name;

    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }
}
