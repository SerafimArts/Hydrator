<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Event;

use Rds\Hydrator\Mapper\Payload\PayloadInterface;

/**
 * Class Event
 */
abstract class Event implements EventInterface
{
    /**
     * @var object
     */
    private $target;

    /**
     * @var PayloadInterface
     */
    private $payload;

    /**
     * @var object|null
     */
    private $context;

    /**
     * Event constructor.
     *
     * @param object $target
     * @param PayloadInterface $payload
     * @param object|null $context
     */
    public function __construct(object $target, PayloadInterface $payload, ?object $context)
    {
        $this->target = $target;
        $this->payload = $payload;
        $this->context = $context;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function match(string $class): bool
    {
        return $this->target instanceof $class;
    }

    /**
     * @param string $class
     * @param \Closure $then
     * @return Event|$this
     */
    public function when(string $class, \Closure $then): self
    {
        if ($this->match($class)) {
            $then($this->target, $this->payload, $this->context);
        }

        return $this;
    }

    /**
     * @return object
     */
    public function getTarget(): object
    {
        return $this->target;
    }

    /**
     * @return PayloadInterface
     */
    public function getPayload(): PayloadInterface
    {
        return $this->payload;
    }

    /**
     * @return object|null
     */
    public function getContext(): ?object
    {
        return $this->context;
    }
}
