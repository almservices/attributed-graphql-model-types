<?php

declare(strict_types=1);

namespace Example\Attribute;

use AlmServices\Graphql\Model\Alias;
use AlmServices\Graphql\Model\Field;
use AlmServices\Graphql\Model\ID;
use AlmServices\Graphql\Model\ListOf;
use AlmServices\Graphql\Model\Model;
use AlmServices\Graphql\Model\NonNull;

#[Model(name: 'AnimalAlias')]
class Animal
{
    #[ID]
    #[Field]
    #[Alias(name: 'animalId')]
    public int $id;

    #[Field]
    public ?string $gangName;

    #[Field]
    public Family $family;

    #[Field]
    #[NonNull]
    #[ListOf(type: 'Float')]
    public array $coordinates;

    #[Field]
    public bool $isSuperior = true;

    private string $privateName;

    public function __construct(int $id, string $privateName, ?string $gangName, Family $family, array $coordinates)
    {
        $this->id = $id;
        $this->privateName = $privateName;
        $this->gangName = $gangName;
        $this->family = $family;
        $this->coordinates = $coordinates;
    }

    #[Alias(name: 'name')]
    #[Field]
    public function getName(): string
    {
        return $this->privateName;
    }
}
