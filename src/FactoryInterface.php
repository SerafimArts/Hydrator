<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

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

    /**
     * @param string $class
     * @return HydratorInterface
     */
    public function new(string $class): HydratorInterface;
}
