# Attributed GraphQL model types

[![Latest stable version](https://img.shields.io/packagist/v/almservices/attributed-graphql-model-types.svg?label=current%20version)](https://packagist.org/packages/almservices/attributed-graphql-model-types)
[![CI Status](https://github.com/almservices/attributed-graphql-model-types/workflows/CI/badge.svg?branch=main)](https://github.com/almservices/attributed-graphql-model-types/actions)
[![PHP version](https://img.shields.io/packagist/php-v/almservices/attributed-graphql-model-types.svg)](https://php.net)
[![License](https://img.shields.io/github/license/almservices/attributed-graphql-model-types.svg)](LICENSE)

# Requirements
* Composer
* PHP >=7.4

# Installation
``composer require almservices/attributed-graphql-model-types``

# Usage
Given we have such model
```php
#[Model(name: "AnimalAlias")]
class Animal
{
    #[ID]
    #[Field]
    public int $id;

    #[Field]
    public string $name;
}
```
or with php7.4

```php
/**
 * @Model(name: "AnimalAlias")
 */
class Animal
{
    /**
     * @ID
     * @Field
     */
    public int $id;

    /**
     * @Field
     */
    public string $name;
}
```

We can create GraphQL type by
```php
class AnimalType extends ModelType
{
    public function __construct(bool $isProd)
    {
        parent::__construct(Animal::class, new TypeContainer($isProd), $isProd);
    }
}
```
or directly by
```php
new ModelType(Animal::class, new TypeContainer($isProd), $isProd);
```

which will be equivalent to

```php
class AnimalType extends \GraphQL\Type\Definition\ObjectType {
    public function __construct() {
        parent::__construct([
            'name' => 'AnimalAlias',
            'fields' => static fn () => [
                'id' => [
                    'resolve' => static fn (Animal $animal) => $animal->id,
                    'type' => self::nonNull(self::id()),
                ],
                'name' => [
                    'resolve' => static fn (Animal $animal) => $animal->name,
                    'type' => self::nonNull(self::string()),
                ],
            ],
        ]);
    }
}
```

# Model Examples

## Field
```php
#[Model(name: "Foo")]
class Foo
{
    #[Field]
    public int $bar;

    #[Field]
    public function baz(): string
    {
        return 'baz';
    }
}
```

## Alias
```php
#[Model(name: "AnimalAlias")]
class Foo
{
    #[Alias("bar")]
    #[Field]
    public int $foo;
}
```

## Enum
PHP 8.1
```php
#[Model(name: "Family")]
enum Family
{
    case SEAL;
    case BEAR;
}
```

inline version for PHP < 8.1
```php
#[Model(name: "Foo")]
class InlineEnum
{
    #[Field]
    #[Enum("SingleEnum", "A", "B", "C", "D")]
    public string $single;

    #[Field]
    #[ListOf("ListEnum")]
    #[Enum("ListEnum", "A", "B", "C", "D")]
    public array $list;
}
```

other options are:
```php
#[Model("SomeEnum")]
class SomeEnum: string
{
    case FOO = 'foo'; // FOO
}
```
```php
#[Model("SomeEnum")]
class SomeEnum: int
{
    case FOO = 1; // FOO
}
```
```php
#[Model("SomeEnum")]
class SomeEnum: string
{
    #[Alias("foo")]
    case FOO = "bar"; // foo
}
```
```php
#[Model("SomeEnum")]
class SomeEnum: int
{
    #[Alias("foo")]
    case FOO = 1; // foo
}
```
Ignoring specific fields:
```php
#[Model("SomeEnum")]
class SomeEnum: int
{
    case FOO;
    case BAR;
    #[Ignore]
    case BAZ;
}
```

## Lists
```php
#[Model("Foo")]
class Foo
{
    #[Field]
    #[ListOf(type: "string")]
    public \Traversable $test; // [String]!

    #[Field]
    #[ListOf(type: "string")]
    public array $foo; // [String]!

    #[Field]
    #[ListOf(type: self::class)]
    public iterable $bar; // [Foo]!

    #[Field]
    #[ListOf(type: OtherModel::class)]
    public array $baz; // [OtherModel]!

    #[Field]
    #[ListOf(type: OtherModel::class)]
    public \Doctrine\Common\Collections\Collection $qux; // [OtherModel]!
}
```

for non-nullable items use NonNull
```php
#[Model("Foo")]
class Foo {
    #[Field]
    #[NonNull]
    #[ListOf("string")]
    public array $list; // [String!]!
}
```

## Value Object
Legacy or custom Value Objects that can be cast to string, can be used as model property
```php
class FooBar implements Stringable
{
    private string $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function foo(): self
    {
        return new self('foo');
    }

    public static function bar(): self
    {
        return new self('foo');
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
```
example:
```php
#[Model(name: "Foo")]
class Foo {
    #[ID]
    #[Field]
    public readonly FooBar $id;
}
```

but if value is more complex it can become resolved on demand

```php
#[Model(name: "Foo")]
class Foo {
    public function __construct(
        private string $foo,
        private string $bar,
    ) {}

    #[ID]
    #[Field]
    public function id(): string
    {
        return $this->foo . $this->bar;
    }
}
```

## Deprecated
```php
#[Model(name: "Foo")]
class Foo {
    #[Field]
    #[Deprecated("Do not use Foo.foo, use Foo.bar instead")]
    public function foo(): string
    {
        return 'foo';
    }

    #[Field]
    public function bar(): string
    {
        return 'bar';
    }
}
```

## Description
```php
#[Description("Foo is Bar")]
#[Model(name: "Foo")]
class Foo {
    #[Field]
    #[Description("Foo foo?")]
    public function foo(): string
    {
        return 'foo';
    }
}
```

## More on resolvers
```php
#[Model("Foo")]
class Foo {
    #[Field]
    #[Argument(name: "bar", type: "string", nullable: false)]
    #[Argument(name: "baz", type: "[String!]!")]
    #[Argument(name: "qux", type: "[string]", nullable: false)]
    public function bar(
        #[ArrayShape([
            "baz" => "string[]"
        ])]
        array $args
    ): string
    {
        return implode(", ", $args['baz']);
    }
}
```

Example with objective input type
```php

class MyInput extends InputObjectType {
    public function __construct()
    {
        parent::__construct("MyInput", $this->fields());
    }

    /**
     * @return \Generator<FieldInterface>
     */
    private function fields(): \Generator
    {
        yield new Field("foo", Type::nonNull(Type::string()));
    }
}

// we need to register MyInput into map
$typeContainer = new TypeContainer();
$typeContainer->set(MyInput::class, new MyInput());
$typeContainer->set("MyInput", new MyInput());

#[Model("Foo")]
class Foo {
    #[Field]
    #[Argument(name: "baz", type: MyInput::class, nullable: false)]
    public function bar(
        #[ArrayShape([
            "baz" => [
                "foo" => "string"
            ]
        ])]
        array $args
    ): string
    {
        return $args['baz']['foo'];
    }
}
```
Example of fully qualified resolver
```php
#[Model("Foo")]
class Foo {
    #[Field]
    public function bar(
        #[ArrayShape([])] array $args,
        Context $context,
        ResolveInfo $resolveInfo
    ): string
    {
        return '';
    }
}
```

# Demo
To run demo execute

``php -S 127.0.0.1:8000 demo/index.php``

``curl 127.0.0.1:8000 -d '{"query": "{myUser{id firstName lastName}}"}' -H "Content-Type: application/json" -H 'Authorization: dev'``
