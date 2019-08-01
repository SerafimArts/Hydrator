<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Mapper;

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
     * @var \Closure
     */
    protected $accessor;

    /**
     * @var \Closure
     */
    protected $mutator;

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

        $this->accessor = $this->propertyAccessor($property);
        $this->mutator = $this->propertyMutator($property);
    }

    /**
     * @param object $instance
     * @param mixed $data
     * @param object $context
     * @return mixed
     */
    public function read(object $instance, array $data, object $context = null): array
    {
        $this->assertPropertyExists($instance, $this->property);

        $data[$this->key] = $this->accessor->call($instance);

        return $data;
    }

    /**
     * @param object $instance
     * @param array $data
     * @param object|null $context
     * @return void
     */
    public function write(object $instance, array $data, object $context = null): void
    {
        $this->assertPropertyExists($instance, $this->property);

        if (\array_key_exists($this->key, $data)) {
            $this->mutator->call($instance, $data[$this->key]);
        }
    }
}
