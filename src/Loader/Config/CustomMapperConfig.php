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
use Rds\Hydrator\Mapper\MapperInterface;

/**
 * Class CustomMapperConfig
 */
class CustomMapperConfig extends Config
{
    /**
     * @param string $name
     * @return bool
     */
    public function match(string $name): bool
    {
        return \class_exists($name) && \is_subclass_of($name, MapperInterface::class, true);
    }

    /**
     * @return bool
     */
    public function isDeferred(): bool
    {
        return true;
    }

    /**
     * @param HydratorInterface $hydrator
     * @param string $class
     * @param array $config
     * @return void
     */
    public function apply(HydratorInterface $hydrator, string $class, array $config): void
    {
        foreach ($config as $arguments) {
            $hydrator->add(new $class(...$arguments));
        }
    }
}
