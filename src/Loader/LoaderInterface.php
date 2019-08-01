<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Loader;

use Rds\Hydrator\FactoryInterface;
use Rds\Hydrator\HydratorInterface;
use Rds\Hydrator\Exception\LoaderException;
use Rds\Hydrator\Loader\Configurator\ConfiguratorInterface;

/**
 * Interface LoaderInterface
 */
interface LoaderInterface
{
    /**
     * @param ConfiguratorInterface $configurator
     * @param string $class
     * @return HydratorInterface[]|null
     */
    public function load(ConfiguratorInterface $configurator, string $class): ?iterable;
}