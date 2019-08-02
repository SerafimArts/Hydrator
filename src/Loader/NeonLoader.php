<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Loader;

use Nette\Neon\Neon;
use Rds\Hydrator\HydratorInterface;
use Rds\Hydrator\Exception\UnsupportedLoaderException;
use Rds\Hydrator\Exception\LoaderConfigurationException;
use Rds\Hydrator\Loader\Configurator\ConfiguratorInterface;

/**
 * Class NeonLoader
 */
class NeonLoader extends Loader
{
    /**
     * @var string
     */
    public const DEFAULT_EXTENSION = '.neon';

    /**
     * YamlLoader constructor.
     *
     * @param string $directory
     * @param string|null $extension
     */
    public function __construct(string $directory, string $extension = null)
    {
        parent::__construct($directory, $extension ?? static::DEFAULT_EXTENSION);
    }

    /**
     * @param ConfiguratorInterface $config
     * @param string $class
     * @return HydratorInterface[]|null
     */
    public function load(ConfiguratorInterface $config, string $class): ?iterable
    {
        return $this->resolve($config, $class, function (string $pathname): array {
            try {
                return (array)Neon::decode($this->read($pathname));
            } catch (\Throwable $e) {
                $message = \sprintf(self::ERROR_READING, $pathname, $e->getMessage());
                throw new LoaderConfigurationException($message);
            }
        });
    }

    /**
     * @return void
     */
    protected function assertIsSupported(): void
    {
        if (! \class_exists(Neon::class)) {
            $message = \sprintf(self::ERROR_UNSUPPORTED_LOADER, static::class, 'nette/neon');

            throw new UnsupportedLoaderException($message);
        }
    }
}
