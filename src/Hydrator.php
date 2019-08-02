<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator;

use Rds\Hydrator\Event\Serialized;
use Rds\Hydrator\Event\Serializing;
use Rds\Hydrator\Event\Instantiated;
use Rds\Hydrator\Event\Instantiating;
use Rds\Hydrator\Mapper\MapperInterface;
use Rds\Hydrator\Mapper\MutatorInterface;
use Rds\Hydrator\Mapper\AccessorInterface;
use Rds\Hydrator\Exception\HydratorException;
use Rds\Hydrator\Mapper\Payload\NestedPayload;
use Rds\Hydrator\Collection\MutatorsCollection;
use Rds\Hydrator\Collection\AccessorsCollection;
use Psr\EventDispatcher\EventDispatcherInterface;
use Rds\Hydrator\Mapper\Payload\PayloadInterface;

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
     * @var EventDispatcherInterface|null
     */
    private $dispatcher;

    /**
     * Hydrator constructor.
     *
     * @param string $class
     * @param EventDispatcherInterface|null $dispatcher
     */
    public function __construct(string $class, EventDispatcherInterface $dispatcher = null)
    {
        try {
            $this->reflection = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new HydratorException($e->getMessage(), $e->getCode());
        }

        $this->dispatcher = $dispatcher;
        $this->accessors = new AccessorsCollection();
        $this->mutators = new MutatorsCollection();
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
     * @return string
     */
    public function getClass(): string
    {
        return $this->reflection->getName();
    }

    /**
     * @param array|PayloadInterface $payload
     * @param object|null $target
     * @param object|null $context
     * @return object
     */
    public function hydrate($payload, object $target = null, object $context = null): object
    {
        $target = $target ?? $this->create();
        $payload = $this->payload($payload);

        if ($this->dispatcher) {
            $this->dispatcher->dispatch(new Instantiating($target, $payload, $context));
        }

        $this->mutators->apply($target, $payload);

        if ($this->dispatcher) {
            $this->dispatcher->dispatch(new Instantiated($target, $payload, $context));
        }

        return $target;
    }

    /**
     * @return object
     */
    protected function create(): object
    {
        return $this->reflection->newInstanceWithoutConstructor();
    }

    /**
     * @param array|\Traversable|PayloadInterface $payload
     * @return PayloadInterface
     */
    protected function payload($payload): PayloadInterface
    {
        switch (true) {
            case \is_array($payload):
                return new NestedPayload($payload);

            case $payload instanceof \Traversable:
                return new NestedPayload(\iterator_to_array($payload));

            default:
                return $payload;
        }
    }

    /**
     * @param object $object
     * @param object|null $context
     * @return array
     */
    public function toArray(object $object, object $context = null): array
    {
        $payload = $this->payload([]);

        if ($this->dispatcher) {
            $this->dispatcher->dispatch(new Serializing($object, $payload, $context));
        }

        $this->accessors->apply($object, $payload);

        if ($this->dispatcher) {
            $this->dispatcher->dispatch(new Serialized($object, $payload, $context));
        }

        return $payload->toArray();
    }
}
