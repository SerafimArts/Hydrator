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
use Rds\Hydrator\Exception\HydratorException;
use Rds\Hydrator\Loader\Configurator\ConfiguratorInterface;

/**
 * Class Loader
 */
abstract class Loader implements LoaderInterface
{
    /**
     * @var string
     */
    protected const ERROR_UNSUPPORTED_LOADER = '%s loader is not supported. Make sure that "%s" is installed';

    /**
     * @var array|string[]
     */
    private $directories = [];

    /**
     * @var string
     */
    private $extension;

    /**
     * Loader constructor.
     *
     * @param string $directory
     * @param string $extension
     */
    public function __construct(string $directory, string $extension)
    {
        $this->assertIsSupported();

        $this->directories[] = $directory;
        $this->extension = $extension;
    }

    /**
     * @return void
     */
    protected function assertIsSupported(): void
    {
        // Override for checking support
    }

    /**
     * @param string $directory
     * @return Loader|$this
     */
    public function withDirectory(string $directory): self
    {
        $this->directories[] = $this->normalizeDirectory($directory);

        return $this;
    }

    /**
     * @param string $path
     * @return string
     */
    private function normalizeDirectory(string $path): string
    {
        return \rtrim(\str_replace('\\', '/', $path), '/') . '/';
    }

    /**
     * @param ConfiguratorInterface $configurator
     * @param string $class
     * @param \Closure $then
     * @return HydratorInterface[]|null
     */
    protected function resolve(ConfiguratorInterface $configurator, string $class, \Closure $then): ?iterable
    {
        if ($file = $this->find($class)) {
            try {
                return $configurator->configure($then($file));
            } catch (HydratorException $e) {
                throw $e;
            } catch (\Throwable $e) {
                throw new LoaderException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return null;
    }

    /**
     * @param string $class
     * @return string|null
     */
    protected function find(string $class): ?string
    {
        $fqn = $this->normalizeNamespace($class);
        $class = \basename(\str_replace('\\', \DIRECTORY_SEPARATOR, $fqn));

        foreach ($this->directories as $namespace => $directory) {
            if (\is_file($pathname = $this->normalizeDirectory($directory) . $fqn . $this->extension)) {
                return $pathname;
            }

            if (\is_file($pathname = $this->normalizeDirectory($directory) . $class . $this->extension)) {
                return $pathname;
            }
        }

        return null;
    }

    /**
     * @param string $namespace
     * @return string
     */
    private function normalizeNamespace(string $namespace): string
    {
        return \trim($namespace, '\\');
    }
}
