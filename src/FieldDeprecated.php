<?php

namespace AlmServices\Graphql;

interface FieldDeprecated
{
    public function deprecationReason(): string;
}
