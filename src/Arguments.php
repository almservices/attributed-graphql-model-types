<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

class Arguments
{
    /** @var array<FieldInterface> */
    private array $fields;

    public function __construct(FieldInterface ...$fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return array<FieldInterface>
     */
    public function fields(): array
    {
        return $this->fields;
    }
}
