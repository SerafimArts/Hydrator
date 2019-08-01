<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Mapper;

use Rds\Hydrator\HydratorInterface;

/**
 * Class Embeddable
 */
class Embeddable implements AccessorInterface, MutatorInterface
{
    use PropertyHelpersTrait;

    /**
     * @var string
     */
    private $property;

    /**
     * @var HydratorInterface
     */
    private $embeddable;

    /**
     * @var \Closure
     */
    private $accessor;

    /**
     * @var \Closure
     */
    private $mutator;

    /**
     * Embeddable constructor.
     *
     * @param string $property
     * @param HydratorInterface $embeddable
     */
    public function __construct(string $property, HydratorInterface $embeddable)
    {
        $this->property = $property;
        $this->embeddable = $embeddable;

        $this->accessor = $this->propertyAccessor($property);
        $this->mutator = $this->propertyMutator($property);
    }

    /**
     * @param object $instance
     * @param array $data
     * @param object|null $context
     * @return array
     */
    public function read(object $instance, array $data, object $context = null): array
    {
        $this->assertPropertyExists($instance, $this->property);

        $embeddable = $this->embeddable->toArray(
            $this->accessor->call($instance),
            $instance
        );

        return \array_merge($data, $embeddable);
    }

    /**
     * @param object $instance
     * @param array $data
     * @param object $context
     * @return void
     */
    public function write(object $instance, array $data, object $context = null): void
    {
        $this->assertPropertyExists($instance, $this->property);

        $embeddable = $this->embeddable->make($data, $instance);

        $this->mutator->call($instance, $embeddable);
    }
}
