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
use Rds\Hydrator\Mapper\Payload\PayloadInterface;

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
     * Embeddable constructor.
     *
     * @param string $property
     * @param HydratorInterface $embeddable
     */
    public function __construct(string $property, HydratorInterface $embeddable)
    {
        $this->property = $property;
        $this->embeddable = $embeddable;
    }

    /**
     * @param object $instance
     * @param PayloadInterface $payload
     * @param object|null $context
     * @return void
     */
    public function read(object $instance, PayloadInterface $payload, object $context = null): void
    {
        $embeddable = $this->access($instance, $this->property);

        foreach ($this->embeddable->toArray($embeddable, $instance) as $key => $value) {
            $payload->set($key, $value);
        }
    }

    /**
     * @param object $instance
     * @param PayloadInterface $payload
     * @param object $context
     * @return void
     */
    public function write(object $instance, PayloadInterface $payload, object $context = null): void
    {
        $embeddable = $this->embeddable->hydrate($payload->toArray(), null, $instance);

        $this->mutate($instance, $this->property, $embeddable);
    }
}
