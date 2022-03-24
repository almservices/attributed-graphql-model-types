<?php

declare(strict_types=1);

namespace AlmServices\Graphql\Model;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @NamedArgumentConstructor
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS | \Attribute::TARGET_CLASS_CONSTANT)]
class Description
{
    public string $description;

    public function __construct(string $description)
    {
        $this->description = $description;
    }
}
