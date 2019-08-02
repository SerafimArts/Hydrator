<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Loader\Config;

use Rds\Hydrator\Mapper\Property;
use Rds\Hydrator\HydratorInterface;

/**
 * Class FieldsConfig
 */
class FieldsConfig extends Config
{
    /**
     * @var string
     */
    private const CONFIG_NAME = 'fields';

    /**
     * @param string $name
     * @return bool
     */
    public function match(string $name): bool
    {
        return self::CONFIG_NAME === $name;
    }

    /**
     * @param HydratorInterface $hydrator
     * @param string $name
     * @param array $config
     * @return void
     */
    public function apply(HydratorInterface $hydrator, string $name, array $config): void
    {
        foreach ($config as $property => $key) {
            $hydrator->add(new Property($property, $key));
        }
    }
}
