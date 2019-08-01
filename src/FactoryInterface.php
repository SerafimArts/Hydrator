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

namespace Rds\Hydrator;

/**
 * Interface FactoryInterface
 */
interface FactoryInterface
{
    /**
     * @param string $class
     * @return HydratorInterface
     */
    public function create(string $class): HydratorInterface;
}
