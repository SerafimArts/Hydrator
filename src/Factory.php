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

use Rds\Hydrator\Loader\LoaderInterface;
use Rds\Hydrator\Loader\Configurator\SimpleConfigurator;
use Rds\Hydrator\Loader\Configurator\ConfiguratorInterface;

/**
 * Class Factory
 */
class Factory implements FactoryInterface
{
    /**
     * @var array|HydratorInterface[]
     */
    private $hydrators = [];

    /**
     * @var array|LoaderInterface[]
     */
    private $loaders;

    /**
     * Factory constructor.
     *
     * @param LoaderInterface ...$loaders
     */
    public function __construct(LoaderInterface ...$loaders)
    {
        $this->loaders = $loaders;
    }

    /**
     * @param LoaderInterface $loader
     * @return ConfiguratorInterface
     */
    private function configurator(LoaderInterface $loader): ConfiguratorInterface
    {
        return new SimpleConfigurator($this);
    }

    /**
     * @param LoaderInterface $loader
     * @return Factory|$this
     */
    public function withLoader(LoaderInterface $loader): self
    {
        $this->loaders[] = $loader;

        return $this;
    }

    /**
     * @param HydratorInterface $hydrator
     * @return Factory|$this
     */
    public function withHydrator(HydratorInterface $hydrator): self
    {
        $this->hydrators[$hydrator->getClass()] = $hydrator;

        return $this;
    }

    /**
     * @param string $class
     * @return HydratorInterface
     */
    public function create(string $class): HydratorInterface
    {
        if (! \array_key_exists($class, $this->hydrators)) {
            $this->hydrators[$class] = $this->fromLoader($class) ?? new Hydrator($class);
        }

        return $this->hydrators[$class];
    }

    /**
     * @param string $class
     * @return HydratorInterface|null
     */
    private function fromLoader(string $class): ?HydratorInterface
    {
        foreach ($this->loaders as $loader) {
            $configurator = $this->configurator($loader);

            if (! $hydrators = $loader->load($configurator, $class)) {
                continue;
            }

            if (! $hydrator = $this->match($class, $hydrators)) {
                continue;
            }

            return $hydrator;
        }

        return null;
    }

    /**
     * @param string $class
     * @param iterable|HydratorInterface[] $hydrators
     * @return HydratorInterface|null
     */
    private function match(string $class, iterable $hydrators): ?HydratorInterface
    {
        $needle = null;

        foreach ($hydrators as $hydrator) {
            if ($hydrator->getClass() === $class) {
                $needle = $hydrator;
            }

            $this->withHydrator($hydrator);
        }

        return $needle;
    }
}
