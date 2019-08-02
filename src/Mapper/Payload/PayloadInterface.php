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
 * Interface PayloadInterface
 */
interface PayloadInterface
{
    /**
     * @param string $key
     * @param \Closure|null $then
     * @return mixed
     */
    public function get(string $key, \Closure $then = null);

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * @param string $key
     * @param mixed $value
     * @return PayloadInterface
     */
    public function with(string $key, $value): self;

    /**
     * @return array
     */
    public function toArray(): array;
}
