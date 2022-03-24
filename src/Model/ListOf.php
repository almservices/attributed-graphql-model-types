<?php

declare(strict_types=1);

namespace AlmServices\Graphql\Model;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @NamedArgumentConstructor
 * @Annotation
 */
#[\Attribute(flags: \Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class ListOf
{
    public string $type;

    public function __construct(
        string $type,
    ) {
        $this->type = $type;
    }
}
