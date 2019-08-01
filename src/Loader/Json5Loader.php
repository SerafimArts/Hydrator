<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Loader;

use Phplrt\Io\File;
use Railt\Json\Json5;
use Rds\Hydrator\HydratorInterface;
use Rds\Hydrator\Exception\LoaderException;
use Rds\Hydrator\Exception\UnsupportedLoaderException;
use Rds\Hydrator\Loader\Configurator\ConfiguratorInterface;

/**
 * Class Json5Loader
 */
class Json5Loader extends Loader
{
    /**
     * @var string
     */
    public const DEFAULT_EXTENSION = '.json5';

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
        return $this->resolve($config, $class, static function (string $pathname): array {
            try {
                return Json5::read(File::fromPathname($pathname), 1);
            } catch (\Throwable $e) {
                throw new LoaderException($e->getMessage());
            }
        });
    }

    /**
     * @return void
     */
    protected function assertIsSupported(): void
    {
        if (! \class_exists(Json5::class)) {
            $message = \sprintf(self::ERROR_UNSUPPORTED_LOADER, static::class, 'railt/json');

            throw new UnsupportedLoaderException($message);
        }
    }
}
