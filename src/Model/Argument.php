<?php

declare(strict_types=1);

namespace AlmServices\Graphql\Model;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @NamedArgumentConstructor
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Argument
{
    public string $name;

    /** @var class-string|string */
    public string $type;
    public bool $nullable;

    /**
     * @param class-string|string $type
     */
    public function __construct(string $name, string $type, bool $nullable = true)
    {
        $this->name = $name;
        $this->type = $type;
        $this->nullable = $nullable;
    }
}
