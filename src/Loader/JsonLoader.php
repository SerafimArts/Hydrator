<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Loader;

use Rds\Hydrator\HydratorInterface;
use Rds\Hydrator\Exception\LoaderException;
use Rds\Hydrator\Exception\UnsupportedLoaderException;
use Rds\Hydrator\Loader\Configurator\ConfiguratorInterface;

/**
 * Class JsonLoader
 */
class JsonLoader extends Loader
{
    /**
     * @var string
     */
    public const DEFAULT_EXTENSION = '.json';

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
            if (! \is_readable($pathname)) {
                throw new LoaderException('Configuration file "' . $pathname . '" not readable');
            }

            $data = @\json_decode(\file_get_contents($pathname), true);

            if (\json_last_error() !== \JSON_ERROR_NONE) {
                throw new LoaderException(\json_last_error_msg());
            }

            return $data;
        });
    }

    /**
     * @return void
     */
    protected function assertIsSupported(): void
    {
        if (! \function_exists('\\json_decode')) {
            $message = \sprintf(self::ERROR_UNSUPPORTED_LOADER, static::class, 'ext-json');

            throw new UnsupportedLoaderException($message);
        }
    }
}
