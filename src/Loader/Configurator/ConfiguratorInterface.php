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

namespace Rds\Hydrator\Loader\Configurator;

use Rds\Hydrator\HydratorInterface;

/**
 * Interface ConfiguratorInterface
 */
interface ConfiguratorInterface
{
    /**
     * @param mixed $config
     * @return HydratorInterface[]
     */
    public function configure($config): iterable;
}
