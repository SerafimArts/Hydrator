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
use Rds\Hydrator\Mapper\Embeddable;

/**
 * Class EmbeddedConfig
 */
class EmbeddedConfig extends Config
{
    /**
     * @var string
     */
    private const ERROR_EMBEDDABLE = 'Can not load embeddable "%s" for property "%s"';

    /**
     * @var string
     */
    private const CONFIG_NAME = 'embedded';

    /**
     * @param string $name
     * @return bool
     */
    public function match(string $name): bool
    {
        return self::CONFIG_NAME === $name;
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
     * @param string $name
     * @param array $config
     * @return void
     */
    public function apply(HydratorInterface $hydrator, string $name, array $config): void
    {
        foreach ($config ?? [] as $property => $class) {
            $this->assertClassExists($class, \sprintf(self::ERROR_EMBEDDABLE, $class, $property));

            $hydrator->add(new Embeddable($property, $this->factory->create($class)));
        }
    }
}

