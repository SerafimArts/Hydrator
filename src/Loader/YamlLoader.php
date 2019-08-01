<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);



namespace Rds\Hydrator\Loader;

use Symfony\Component\Yaml\Yaml;
use Rds\Hydrator\HydratorInterface;
use Rds\Hydrator\Exception\LoaderException;
use Symfony\Component\Yaml\Exception\ParseException;
use Rds\Hydrator\Exception\UnsupportedLoaderException;
use Rds\Hydrator\Loader\Configurator\ConfiguratorInterface;

/**
 * Class YamlLoader
 */
class YamlLoader extends Loader
{
    /**
     * @var string
     */
    public const DEFAULT_EXTENSION = '.yml';

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
                return Yaml::parseFile($pathname);
            } catch (ParseException $e) {
                throw new LoaderException($e->getMessage(), $e->getCode(), $e);
            }
        });
    }

    /**
     * @return void
     */
    protected function assertIsSupported(): void
    {
        if (! \class_exists(Yaml::class)) {
            $message = \sprintf(self::ERROR_UNSUPPORTED_LOADER, static::class, 'symfony/yaml');

            throw new UnsupportedLoaderException($message);
        }
    }
}
