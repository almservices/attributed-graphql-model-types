<?php

declare(strict_types=1);

namespace AlmServices\Graphql\Model;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @NamedArgumentConstructor
 * @Annotation
 */
#[\Attribute(flags: \Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class Enum
{
    public string $name;

    /** @var array<string> */
    public array $values;

    /**
     * @param array<string> $values
     */
    public function __construct(string $name, array $values)
    {
        $this->name = $name;
        $this->values = $values;
    }
}
