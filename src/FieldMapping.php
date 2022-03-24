<?php

namespace AlmServices\Graphql;

interface FieldMapping
{
    /**
     * @param mixed $input
     *
     * @return mixed
     */
    public function map($input);
}
