<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator;

use Rds\Hydrator\Mapper\Property;
use Rds\Hydrator\Mapper\Embeddable;
use Rds\Hydrator\Mapper\NestedProperty;
use Rds\Hydrator\Mapper\MapperInterface;
use Rds\Hydrator\Mapper\MutatorInterface;
use Rds\Hydrator\Mapper\AccessorInterface;
use Rds\Hydrator\Exception\HydratorException;
use Rds\Hydrator\Collection\MutatorsCollection;
use Rds\Hydrator\Collection\AccessorsCollection;
use Rds\Hydrator\Collection\CollectionInterface;

/**
 * Class Hydrator
 */
class Hydrator implements HydratorInterface
{
    /**
     * @var \ReflectionClass
     */
    protected $reflection;

    /**
     * @var MutatorsCollection|MutatorInterface[]
     */
    private $mutators;

    /**
     * @var AccessorsCollection|AccessorInterface[]
     */
    private $accessors;

    /**
     * @var array|\Closure[]
     */
    private $onCreate = [];

    /**
     * Hydrator constructor.
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        try {
            $this->reflection = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new HydratorException($e->getMessage(), $e->getCode());
        }

        $this->accessors = new AccessorsCollection();
        $this->mutators = new MutatorsCollection();
    }

    /**
     * @param \Closure $then
     * @return Hydrator
     */
    public function onCreate(\Closure $then): self
    {
        $this->onCreate[] = $then;

        return $this;
    }

    /**
     * @param string $class
     * @return Hydrator|$this|static
     */
    public static function new(string $class): self
    {
        return new static($class);
    }

    /**
     * @param iterable $properties
     * @return Hydrator|$this
     */
    public function properties(iterable $properties): self
    {
        foreach ($properties as $property => $key) {
            $this->property(\is_int($property) ? $key : $property, $key);
        }

        return $this;
    }

    /**
     * @param string $property
     * @param string|null $key
     * @return Hydrator|self
     */
    public function property(string $property, string $key = null): self
    {
        return $this->add(new Property($property, $key));
    }

    /**
     * @param MapperInterface|AccessorInterface|MutatorInterface $mapper
     * @return HydratorInterface|$this
     */
    public function add(MapperInterface $mapper): HydratorInterface
    {
        if ($mapper instanceof AccessorInterface) {
            $this->accessors->add($mapper);
        }

        if ($mapper instanceof MutatorInterface) {
            $this->mutators->add($mapper);
        }

        return $this;
    }

    /**
     * @param string $property
     * @param HydratorInterface $hydrator
     * @return Hydrator
     */
    public function embedded(string $property, HydratorInterface $hydrator): self
    {
        return $this->add(new Embeddable($property, $hydrator));
    }

    /**
     * @param string $property
     * @param string|null $key
     * @param string|null $delimiter
     * @return Hydrator|self
     */
    public function nested(string $property, string $key = null, string $delimiter = null): self
    {
        return $this->add(new NestedProperty($property, $key, $delimiter));
    }

    /**
     * @return AccessorsCollection|CollectionInterface
     */
    public function accessors(): CollectionInterface
    {
        return $this->accessors;
    }

    /**
     * @return MutatorsCollection|CollectionInterface
     */
    public function mutators(): CollectionInterface
    {
        return $this->mutators;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->reflection->getName();
    }

    /**
     * @param array $data
     * @param object|null $context
     * @return object
     */
    public function make(array $data, object $context = null): object
    {
        $instance = $this->mutators->apply($this->create(), $data);

        foreach ($this->onCreate as $handler) {
            $handler($instance, $data, $context);
        }

        return $instance;
    }

    /**
     * @return object
     */
    public function create(): object
    {
        return $this->reflection->newInstanceWithoutConstructor();
    }

    /**
     * @param object $object
     * @param object|null $context
     * @return array
     */
    public function toArray(object $object, object $context = null): array
    {
        return $this->accessors->apply($object, []);
    }
}
