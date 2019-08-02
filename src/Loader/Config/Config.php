<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Loader\Config;

use Rds\Hydrator\FactoryInterface;
use Rds\Hydrator\Exception\LoaderConfigurationException;

/**
 * Class Config
 */
abstract class Config implements ConfigInterface
{
    /**
     * @var string
     */
    private const ERROR_CLASS_NOT_FOUND = 'Class "%s" not found or could not be loaded';

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * Config constructor.
     *
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return bool
     */
    public function isDeferred(): bool
    {
        return false;
    }

    /**
     * @param string $class
     * @param string $prefix
     * @return void
     */
    protected function assertClassExists(string $class, string $prefix): void
    {
        if (! $this->classExists($class)) {
            $message = \sprintf(self::ERROR_CLASS_NOT_FOUND, $class);


            throw new LoaderConfigurationException($prefix . '. ' . $message);
        }
    }

    /**
     * @param string $class
     * @return bool
     */
    protected function classExists(string $class): bool
    {
        return \class_exists($class);
    }
}
