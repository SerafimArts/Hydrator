<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator;

use Rds\Hydrator\Loader\LoaderInterface;
use Rds\Hydrator\Loader\Config\FieldsConfig;
use Rds\Hydrator\Loader\Config\EmbeddedConfig;
use Rds\Hydrator\Loader\Config\AttributesConfig;
use Psr\EventDispatcher\EventDispatcherInterface;
use Rds\Hydrator\Loader\Config\CustomMapperConfig;
use Symfony\Component\EventDispatcher\EventDispatcher;
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
     * @var EventDispatcherInterface|EventDispatcher
     */
    private $dispatcher;

    /**
     * Factory constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher = null)
    {
        $this->dispatcher = $dispatcher ?? new EventDispatcher();
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
     * @param string $class
     * @return HydratorInterface
     */
    public function create(string $class): HydratorInterface
    {
        if (! \array_key_exists($class, $this->hydrators)) {
            $this->hydrators[$class] = $this->fromLoader($class) ?? $this->new($class);
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
     * @param LoaderInterface $loader
     * @return ConfiguratorInterface
     */
    private function configurator(LoaderInterface $loader): ConfiguratorInterface
    {
        $configurator = new SimpleConfigurator($this);

        $configurator->withConfig(new FieldsConfig($this));
        $configurator->withConfig(new AttributesConfig($this));
        $configurator->withConfig(new EmbeddedConfig($this));
        $configurator->withConfig(new CustomMapperConfig($this));

        return $configurator;
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
    public function new(string $class): HydratorInterface
    {
        return new Hydrator($class, $this->dispatcher);
    }
}
