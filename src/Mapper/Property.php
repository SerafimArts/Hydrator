<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Mapper;

use Rds\Hydrator\Mapper\Payload\PayloadInterface;

/**
 * Class Property
 */
class Property implements AccessorInterface, MutatorInterface
{
    use PropertyHelpersTrait;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $property;

    /**
     * PropertyReader constructor.
     *
     * @param string $property
     * @param string|null $key
     */
    public function __construct(string $property, string $key = null)
    {
        $this->property = $property;
        $this->key = $key ?? $property;
    }

    /**
     * @param object $instance
     * @param PayloadInterface $payload
     * @param object $context
     * @return void
     */
    public function read(object $instance, PayloadInterface $payload, object $context = null): void
    {
        $payload->set($this->key, $this->access($instance, $this->property));
    }

    /**
     * @param object $instance
     * @param PayloadInterface $payload
     * @param object|null $context
     * @return void
     */
    public function write(object $instance, PayloadInterface $payload, object $context = null): void
    {
        $payload->get($this->key, function ($value) use ($instance) {
            $this->mutate($instance, $this->property, $value);
        });
    }
}
