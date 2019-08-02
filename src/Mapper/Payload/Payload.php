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
 * Class Payload
 */
class Payload implements PayloadInterface
{
    /**
     * @var array
     */
    protected $data;

    /**
     * Payload constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @param \Closure|null $then
     * @return mixed|null
     */
    public function get(string $key, \Closure $then = null)
    {
        /** @noinspection NotOptimalIfConditionsInspection */
        if (isset($this->data[$key]) || \array_key_exists($key, $this->data)) {
            return $then ? $then($this->data[$key]) : $this->data[$key];
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return PayloadInterface
     */
    public function with(string $key, $value): PayloadInterface
    {
        return new static(\array_merge($this->data, [$key => $value]));
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
