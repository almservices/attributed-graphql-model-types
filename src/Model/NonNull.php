<?php

namespace AlmServices\Graphql\Model;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @NamedArgumentConstructor
 * @Annotation
 * Applicable for lists specifically.
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
class NonNull
{
}
