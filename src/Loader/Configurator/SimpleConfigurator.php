<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Loader\Configurator;

use JsonSchema\Validator;
use Rds\Hydrator\FactoryInterface;
use Rds\Hydrator\HydratorInterface;
use JsonSchema\Exception\ExceptionInterface;
use Rds\Hydrator\Loader\Config\ConfigInterface;
use Rds\Hydrator\Exception\LoaderConfigurationException;

/**
 * Class SimpleConfigurator
 */
class SimpleConfigurator implements ConfiguratorInterface
{
    /**
     * @var string
     */
    protected const JSON_SCHEMA_PATHNAME = __DIR__ . '/../../../resources/config.schema.json';

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var array|ConfigInterface[]
     */
    private $configs = [];

    /**
     * SimpleConfigurator constructor.
     *
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->validator = new Validator();
    }

    /**
     * @param ConfigInterface $config
     * @return SimpleConfigurator|$this
     */
    public function withConfig(ConfigInterface $config): self
    {
        $this->configs[] = $config;

        return $this;
    }

    /**
     * @param array $config
     * @return HydratorInterface[]
     */
    public function configure($config): iterable
    {
        \assert(\is_array($config));

        $this->validate($config);

        $hydrators = [];

        foreach ($config as $class => $data) {
            yield $hydrators[$class] = $this->create($class, $data);
        }

        foreach ($hydrators as $class => $hydrator) {
            $this->deferred($hydrator, $config[$class]);
        }
    }

    /**
     * @param array $config
     * @return void
     */
    private function validate(array $config): void
    {
        try {
            $this->validator->validate($config, $this->ref(), Validator::ERROR_ALL);
        } catch (ExceptionInterface $e) {
            throw new LoaderConfigurationException($e->getMessage());
        }
    }

    /**
     * @return array
     */
    private function ref(): array
    {
        return [
            '$ref' => 'file://' . static::JSON_SCHEMA_PATHNAME,
        ];
    }

    /**
     * @param string $class
     * @param array $config
     * @return HydratorInterface
     */
    private function create(string $class, array $config): HydratorInterface
    {
        $hydrator = $this->factory->new($class);

        foreach ($this->configs as $expr) {
            foreach ($config as $key => $data) {
                if ($expr->match($key) && ! $expr->isDeferred()) {
                    $expr->apply($hydrator, $key, $data);
                }
            }
        }

        return $hydrator;
    }

    /**
     * @param HydratorInterface $hydrator
     * @param array $config
     * @return HydratorInterface
     */
    private function deferred(HydratorInterface $hydrator, array $config): HydratorInterface
    {
        foreach ($this->configs as $expr) {
            foreach ($config as $key => $data) {
                if ($expr->match($key) && $expr->isDeferred()) {
                    $expr->apply($hydrator, $key, $data);
                }
            }
        }

        return $hydrator;
    }
}
