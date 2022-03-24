<?php

declare(strict_types=1);

namespace AlmServices\Graphql\Model;

class Source
{
    private \Closure $resolver;
    private ?\ReflectionType $reflectionType;
    private \Generator $attributes;

    /** @var iterable<object> */
    private iterable $collectedAttributes;
    private string $name;
    private SourceType $sourceType;

    /**
     * @param \Generator<object> $attributes
     */
    public function __construct(string $name, \Closure $resolver, ?\ReflectionType $reflectionType, \Generator $attributes, SourceType $sourceType)
    {
        $this->resolver = $resolver;
        $this->reflectionType = $reflectionType;
        $this->attributes = $attributes;
        $this->name = $name;
        $this->sourceType = $sourceType;
    }

    public function resolver(): \Closure
    {
        return $this->resolver;
    }

    public function reflectionType(): ?\ReflectionType
    {
        return $this->reflectionType;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function debugName(): string
    {
        return $this->name().(SourceType::method()->equals($this->sourceType()) ? '()' : '');
    }

    public function sourceType(): SourceType
    {
        return $this->sourceType;
    }

    /**
     * @template T
     *
     * @param class-string<T> $class
     *
     * @return null|T
     */
    public function getAttribute(string $class)
    {
        foreach ($this->attributes() as $attribute) {
            if ($attribute instanceof $class) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * @template T
     *
     * @param class-string<T> $class
     *
     * @return iterable<T>
     */
    public function getAttributes(string $class): iterable
    {
        foreach ($this->attributes() as $attribute) {
            if ($attribute instanceof $class) {
                yield $attribute;
            }
        }
    }

    /**
     * @return iterable<object>
     */
    private function attributes(): iterable
    {
        if (!isset($this->collectedAttributes)) {
            $this->collectedAttributes = [];
            foreach ($this->attributes as $attribute) {
                $this->collectedAttributes[] = $attribute;
            }
        }

        return $this->collectedAttributes;
    }
}
