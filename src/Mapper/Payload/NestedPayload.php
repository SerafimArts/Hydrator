<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Mapper\Payload;

/**
 * Class NestedPayload
 */
class NestedPayload extends Payload
{
    /**
     * @var string
     */
    private const DEFAULT_DELIMITER = '.';

    /**
     * @var string
     */
    protected $delimiter;

    /**
     * NestedPayload constructor.
     *
     * @param array $data
     * @param string|null $delimiter
     */
    public function __construct(array $data, string $delimiter = null)
    {
        parent::__construct($data);

        $this->delimiter = $delimiter ?? self::DEFAULT_DELIMITER;
    }

    /**
     * @param string $key
     * @param \Closure|null $then
     * @return array|mixed|null
     */
    public function get(string $key, \Closure $then = null)
    {
        $array = $this->data;

        /** @noinspection NotOptimalIfConditionsInspection */
        if (isset($array[$key]) || \array_key_exists($key, $array)) {
            return $then ? $then($array[$key]) : $array[$key];
        }

        foreach (\explode($this->delimiter, $key) as $chunk) {
            /** @noinspection NotOptimalIfConditionsInspection */
            if (isset($array[$chunk]) || \array_key_exists($chunk, $array)) {
                $array = $array[$chunk];
            } else {
                return null;
            }
        }

        return $then ? $then($array) : $array;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->insert($this->data, $key, $value);
    }

    /**
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private function insert(array &$array, string $key, $value): void
    {
        $keys = \explode($this->delimiter, $key);

        while (\count($keys) > 1) {
            $key = \array_shift($keys);

            if (! isset($array[$key]) || ! \is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[\array_shift($keys)] = $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return PayloadInterface
     */
    public function with(string $key, $value): PayloadInterface
    {
        $data = $this->data;

        $this->insert($data, $key, $value);

        return new static($data, $this->delimiter);
    }
}
