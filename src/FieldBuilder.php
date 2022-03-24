<?php

declare(strict_types=1);

namespace AlmServices\Graphql;

use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\Type;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PsrPrinter;

/**
 * @template T
 */
class FieldBuilder
{
    private string $name;

    private string $description;
    private string $deprecationReason;

    /** @var NullableType|Type|(\Closure(): (NullableType|Type)) */
    private $type;

    /** @var null|T */
    private $defaultValue;
    private \Closure $mapping;
    private \Closure $resolver;

    /** @var array<FieldInterface> */
    private array $args = [];
    private FieldBuilderStrategy $strategy;

    /**
     * @param NullableType|Type|(\Closure(): (NullableType|Type)) $type
     */
    public function __construct(string $name, $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->strategy = new FieldBuilderStrategy((int) (getenv('GRAPHQL_FIELD_BUILDER_STRATEGY') ?: 2));
    }

    /**
     * @return FieldBuilder<T>
     */
    public function setResolver(\Closure $resolver): self
    {
        $this->resolver = $resolver;

        return $this;
    }

    /**
     * @return FieldBuilder<T>
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return FieldBuilder<T>
     */
    public function addArgument(FieldInterface $arg): self
    {
        $this->args[] = $arg;

        return $this;
    }

    /**
     * @return FieldBuilder<T>
     */
    public function setDeprecationReason(string $deprecationReason): self
    {
        $this->deprecationReason = $deprecationReason;

        return $this;
    }

    /**
     * @return FieldBuilder<T>
     */
    public function setMapping(\Closure $mapping): self
    {
        $this->mapping = $mapping;

        return $this;
    }

    /**
     * @param T $value
     *
     * @return FieldBuilder<T>
     */
    public function setDefaultValue($value): self
    {
        $this->defaultValue = $value;

        return $this;
    }

    public function build(): FieldInterface
    {
        $values = [$this->name, $this->type];
        $class = new ClassType(null);
        $class->addProperty('name')
            ->setPrivate()
            ->setType('string')
        ;
        $class->addMethod('name')
            ->setPublic()
            ->setReturnType('string')
            ->setBody('return $this->name;')
        ;
        $class->addProperty('type')
            ->setPrivate()
            ->setComment('@var callable|Type')
        ;
        $class->addMethod('type')
            ->setPublic()
            ->setReturnType('Type|\Closure')
            ->setComment('@return callable|Type')
            ->setBody('return $this->type;')
        ;
        $class->addImplement($this->fixNetteType(FieldInterface::class));

        $constructorMethod = $class->addMethod('__construct')
            ->setPublic()
        ;

        $constructorMethod->addParameter('name')
            ->setType('string')
        ;
        $constructorMethod->addParameter('type');
        $constructorMethod->addComment('@param callable|Type $type');

        if (isset($this->resolver)) {
            $values[] = $this->resolver;
            $class->addImplement($this->fixNetteType(Resolvable::class));
            $constructorMethod
                ->addParameter('resolver')
                ->setType($this->fixNetteType(\Closure::class))
            ;

            $class->addProperty('resolver')
                ->setPrivate()
                ->setType($this->fixNetteType(\Closure::class))
            ;

            $class->addMethod('resolver')
                ->setPublic()
                ->setReturnType('callable')
                ->setBody('return $this->resolver;')
            ;
        }
        if (isset($this->description)) {
            $values[] = $this->description;
            $class->addImplement($this->fixNetteType(FieldDescribable::class));
            $class->addProperty('description')
                ->setPrivate()
                ->setType('string')
            ;

            $constructorMethod
                ->addParameter('description')
                ->setType('string')
            ;

            $class->addMethod('description')
                ->setPublic()
                ->setReturnType('string')
                ->setBody('return $this->description;')
            ;
        }
        if (isset($this->deprecationReason)) {
            $values[] = $this->deprecationReason;
            $class->addImplement($this->fixNetteType(FieldDeprecated::class));
            $class->addProperty('deprecationReason')
                ->setPrivate()
                ->setType('string')
            ;

            $constructorMethod
                ->addParameter('deprecationReason')
                ->setType('string')
            ;

            $class->addMethod('deprecationReason')
                ->setPublic()
                ->setReturnType('string')
                ->setBody('return $this->deprecationReason;')
            ;
        }
        if (isset($this->defaultValue)) {
            $values[] = $this->defaultValue;
            $class->addImplement($this->fixNetteType(FieldDefaultValue::class));
            $class->addProperty('defaultValue')
                ->setPrivate()
            ;

            $constructorMethod
                ->addParameter('defaultValue')
            ;

            $class->addMethod('defaultValue')
                ->setPublic()
                ->setBody('return $this->defaultValue;')
            ;
        }
        if (!empty($this->args)) {
            $values[] = new Arguments(...$this->args);
            $class->addImplement($this->fixNetteType(Argumentable::class));
            $class->addProperty('args')
                ->setType($this->fixNetteType(Arguments::class))
                ->setPrivate()
            ;

            $constructorMethod
                ->addParameter('args')
                ->setType($this->fixNetteType(Arguments::class))
            ;

            $class->addMethod('args')
                ->setPublic()
                ->setReturnType($this->fixNetteType(Arguments::class))
                ->setBody('return $this->args;')
            ;
        }
        if (isset($this->mapping)) {
            $values[] = $this->mapping;
            $class->addImplement($this->fixNetteType(FieldMapping::class));
            $class->addProperty('mapping')
                ->setType($this->fixNetteType(\Closure::class))
                ->setPrivate()
            ;

            $constructorMethod
                ->addParameter('mapping')
                ->setType($this->fixNetteType(\Closure::class))
            ;

            $class->addMethod('map')
                ->setPublic()
                ->setBody('return ($this->mapping)($value);')
                ->addParameter('value')
            ;
        }

        if (2 === count($class->getImplements()) && isset($this->resolver)) {
            return new FieldResolvable($this->name, $this->type, $this->resolver);
        }

        if (1 === count($class->getImplements())) {
            return new Field($this->name, $this->type);
        }

        $className = str_replace('\\AlmServices\\Graphql\\', '', 'GeneratedFieldV1'.implode('', array_slice($class->getImplements(), 1)));
        $class->setName($className);
        $namespace = 'AlmServices\\Graphql\\Cache';

        $fqcn = $this->createClass($class, $namespace);

        $field = new $fqcn(...$values);
        assert($field instanceof FieldInterface);

        return $field;
    }

    /**
     * @return FieldBuilder<T>
     */
    public function setStrategy(FieldBuilderStrategy $strategy): self
    {
        $this->strategy = $strategy;

        return $this;
    }

    private function fixNetteType(string $class): string
    {
        return '\\'.$class;
    }

    private function createClass(ClassType $class, string $namespace): string
    {
        $fqcn = '\\'.$namespace.'\\'.$class->getName();
        $dir = getcwd().'/var/almservices/graphql/generated/';
        $path = $dir.$class->getName().'.php';

        $constructorMethod = $class->getMethod('__construct');

        if ($this->strategy->is(FieldBuilderStrategy::file())) {
            if (file_exists($path)) {
                require_once $path;

                return $fqcn;
            }
        }

        if (class_exists($fqcn)) {
            return $fqcn;
        }

        foreach ($constructorMethod->getParameters() as $constructorParameter) {
            $constructorMethod->addBody('$this->'.$constructorParameter->getName().' = $'.$constructorParameter->getName().';');
        }

        $printer = new PsrPrinter();

        if ($this->strategy->equals(FieldBuilderStrategy::virtual())) {
            eval('namespace '.$namespace.";\n\nuse GraphQL\\Type\\Definition\\Type;\n\n".$printer->printClass($class));
        } else {
            $classString = "<?php\n\nnamespace ".$namespace.";\n\nuse GraphQL\\Type\\Definition\\Type;\n\n".$printer->printClass($class);

            try {
                @mkdir($dir, 0777, true);
                file_put_contents($path, $classString);

                require_once $path;
            } catch (\Throwable $t) {
                if (!$this->strategy->is(FieldBuilderStrategy::virtual())) {
                    throw $t;
                }
                eval('namespace '.$namespace.";\n\nuse GraphQL\\Type\\Definition\\Type;\n\n".$printer->printClass($class));
            }
        }

        return $fqcn;
    }
}
