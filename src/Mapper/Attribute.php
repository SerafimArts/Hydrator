<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Mapper;

use Rds\Hydrator\Mapper\Payload\NestedPayload;
use Rds\Hydrator\Mapper\Payload\PayloadInterface;
use Rds\Hydrator\Exception\InvalidMappingsException;

/**
 * Class Attribute
 */
class Attribute implements AccessorInterface, MutatorInterface
{
    use PropertyHelpersTrait;

    /**
     * @var string
     */
    private const ERROR_ATTRIBUTE_ARRAY_ACCESS =
        'The property $%s of the class %s must be ' .
        'an array or instance of \ArrayAccess, but %s given';

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $property;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * PropertyReader constructor.
     *
     * @param string $attribute
     * @param string $property
     * @param string|null $key
     */
    public function __construct(string $attribute, string $property, string $key = null)
    {
        $this->attribute = $attribute;
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
        $value = $this->getAttributes($instance)->get($this->property);

        $payload->set($this->key, $value);
    }

    /**
     * @param object $context
     * @return NestedPayload
     */
    protected function getAttributes(object $context): NestedPayload
    {
        $attributes = $this->createAttributes($this->access($context, $this->attribute));

        $this->assertIsAccessible($context, $attributes);

        return new NestedPayload($attributes);
    }

    /**
     * @param mixed $value
     * @return array|\ArrayAccess|mixed
     */
    protected function createAttributes($value)
    {
        return $value ?? [];
    }

    /**
     * @param object $context
     * @param array|\ArrayAccess $attributes
     * @return void
     */
    private function assertIsAccessible(object $context, $attributes): void
    {
        if (! \is_array($attributes) && ! $attributes instanceof \ArrayAccess) {
            $message = \vsprintf(self::ERROR_ATTRIBUTE_ARRAY_ACCESS, [
                $this->attribute,
                \get_class($context),
                \gettype($attributes),
            ]);

            throw new InvalidMappingsException($message);
        }
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
            $attributes = $this->getAttributes($instance);

            $attributes->set($this->property, $value);

            $this->setAttributes($instance, $attributes->toArray());
        });
    }

    /**
     * @param object $context
     * @param array $data
     * @return void
     */
    protected function setAttributes(object $context, array $data): void
    {
        $this->mutate($context, $this->attribute, $data);
    }
}
