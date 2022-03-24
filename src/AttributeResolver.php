<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class AttributeResolver
{
    public static bool $annotations = \PHP_VERSION_ID <= 80000;
    private static AnnotationReader $reader;
    private static bool $init = false;

    /**
     * @template T
     *
     * @param class-string|object|\ReflectionClass $classOrObject
     * @param class-string<T>                      $attributeName
     *
     * @return null|T
     */
    public static function getClassAttribute($classOrObject, string $attributeName)
    {
        self::init();

        if (!class_exists($attributeName)) {
            return null;
        }

        $reflection = $classOrObject instanceof \ReflectionClass ? $classOrObject : new \ReflectionClass($classOrObject);

        if (\PHP_VERSION_ID >= 80000) {
            foreach ($reflection->getAttributes($attributeName) as $attribute) {
                return $attribute->newInstance();
            }
        }

        if (!self::$annotations) {
            return null;
        }

        $annotations = self::$reader->getClassAnnotations($reflection);
        foreach ($annotations as $annotation) {
            if ($annotation instanceof $attributeName) {
                return $annotation;
            }
        }

        return null;
    }

    public static function getMethodAttributes(\ReflectionMethod $method): \Generator
    {
        self::init();
        if (\PHP_VERSION_ID >= 80000) {
            foreach ($method->getAttributes() as $attribute) {
                yield $attribute->newInstance();
            }
        }

        if (!self::$annotations) {
            return;
        }

        foreach (self::$reader->getMethodAnnotations($method) as $annotation) {
            yield $annotation;
        }
    }

    public static function getPropertyAttributes(\ReflectionProperty $property): \Generator
    {
        self::init();
        if (\PHP_VERSION_ID >= 80000) {
            foreach ($property->getAttributes() as $attribute) {
                yield $attribute->newInstance();
            }
        }

        if (!self::$annotations) {
            return;
        }

        foreach (self::$reader->getPropertyAnnotations($property) as $annotation) {
            yield $annotation;
        }
    }

    private static function init(): void
    {
        if (!self::$annotations) {
            return;
        }

        if (self::$init) {
            return;
        }

        AnnotationRegistry::registerUniqueLoader('class_exists');
        self::$reader = new AnnotationReader();
        self::$init = true;
    }
}
