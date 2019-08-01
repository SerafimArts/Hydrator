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
use Rds\Hydrator\Hydrator;
use Rds\Hydrator\Mapper\Property;
use Rds\Hydrator\FactoryInterface;
use Rds\Hydrator\HydratorInterface;
use Rds\Hydrator\Mapper\Embeddable;
use Rds\Hydrator\Mapper\NestedProperty;
use JsonSchema\Exception\ExceptionInterface;
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
     * @var string
     */
    private const ERROR_EMBEDDABLE = 'Can not load embeddable "%s" for property "%s"';

    /**
     * @var string
     */
    private const ERROR_CLASS_NOT_FOUND = 'Class "%s" not found or could not be loaded';

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var FactoryInterface
     */
    private $factory;

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
            $this->resolve($hydrator, $config[$class]);
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
        $hydrator = $this->createHydrator($class);

        foreach ($config['fields'] ?? [] as $property => $key) {
            $hydrator->add(new Property($property, $key));
        }

        foreach ($config['nested'] ?? [] as $property => $key) {
            $hydrator->add(new NestedProperty($property, $key));
        }

        return $hydrator;
    }

    /**
     * @param string $class
     * @return HydratorInterface
     */
    protected function createHydrator(string $class): HydratorInterface
    {
        return new Hydrator($class);
    }

    /**
     * @param HydratorInterface $hydrator
     * @param array $config
     * @return HydratorInterface
     */
    private function resolve(HydratorInterface $hydrator, array $config): HydratorInterface
    {
        foreach ($config['embedded'] ?? [] as $property => $class) {
            $message = \sprintf(self::ERROR_EMBEDDABLE, $class, $property);
            $this->assertClassExists($class, $message);

            $hydrator->add(new Embeddable($property, $this->factory->create($class)));
        }

        return $hydrator;
    }

    /**
     * @param string $class
     * @param string $prefix
     * @return void
     */
    private function assertClassExists(string $class, string $prefix): void
    {
        if (! \class_exists($class)) {
            $message = \sprintf(self::ERROR_CLASS_NOT_FOUND, $class);
            throw new LoaderConfigurationException($prefix . '. ' . $message);
        }
    }
}
