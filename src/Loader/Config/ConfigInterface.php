<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Loader\Config;

use Rds\Hydrator\HydratorInterface;

/**
 * Interface ConfigInterface
 */
interface ConfigInterface
{
    /**
     * @param string $name
     * @return bool
     */
    public function match(string $name): bool;

    /**
     * @return bool
     */
    public function isDeferred(): bool;

    /**
     * @param HydratorInterface $hydrator
     * @param string $key
     * @param array $config
     * @return void
     */
    public function apply(HydratorInterface $hydrator, string $key, array $config): void;
}
