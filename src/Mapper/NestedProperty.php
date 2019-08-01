<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

/*
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rds\Hydrator\Mapper;

/**
 * Class NestedProperty
 */
class NestedProperty extends Property
{
    /**
     * @var string
     */
    public const DEFAULT_DELIMITER = '.';

    /**
     * @var string
     */
    private $delimiter;

    /**
     * DotProperty constructor.
     *
     * @param string $property
     * @param string|null $key
     * @param string|null $delimiter
     */
    public function __construct(string $property, string $key = null, string $delimiter = null)
    {
        parent::__construct($property, $key);

        $this->delimiter = $delimiter ?? self::DEFAULT_DELIMITER;
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

        $this->insert($data, $this->accessor->call($instance));

        return $data;
    }

    /**
     * @param array $array
     * @param mixed $value
     * @return void
     */
    private function insert(array &$array, $value): void
    {
        $keys = \explode($this->delimiter, $this->key);

        while (\count($keys) > 1) {
            $key = \array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (! isset($array[$key]) || ! \is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[\array_shift($keys)] = $value;
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

        $this->lookup($data, function ($data) use ($instance): void {
            $this->mutator->call($instance, $data);
        });
    }

    /**
     * @param array $array
     * @param \Closure $then
     * @return void
     */
    private function lookup(array $array, \Closure $then): void
    {
        if (\array_key_exists($this->key, $array)) {
            $then($array[$this->key]);

            return;
        }

        foreach (\explode($this->delimiter, $this->key) as $chunk) {
            if (\array_key_exists($chunk, $array)) {
                $array = $array[$chunk];
            } else {
                return;
            }
        }

        $then($array);
    }
}
