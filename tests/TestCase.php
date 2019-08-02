<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Tests;

use Rds\Hydrator\Loader\PhpLoader;
use Rds\Hydrator\Loader\JsonLoader;
use Rds\Hydrator\Loader\NeonLoader;
use Rds\Hydrator\Loader\YamlLoader;
use Rds\Hydrator\Loader\Json5Loader;
use Rds\Hydrator\Loader\LoaderInterface;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @return EventDispatcher
     */
    protected function dispatcher(): EventDispatcher
    {
        return new EventDispatcher();
    }

    /**
     * @param string $path
     * @return iterable|LoaderInterface[]
     */
    protected function loaders(string $path = __DIR__ . '/config'): iterable
    {
        yield new PhpLoader($path);
        yield new JsonLoader($path);
        yield new Json5Loader($path);
        yield new YamlLoader($path);
        yield new NeonLoader($path);
    }

    /**
     * @param object $object
     * @param string $property
     * @return mixed
     */
    protected function access(object $object, string $property)
    {
        return (function () use ($property) {
            return $this->$property;
        })->call($object);
    }

    /**
     * @param object $object
     * @param string $property
     * @param mixed $value
     * @return mixed
     */
    protected function mutate(object $object, string $property, $value)
    {
        return (function ($value) use ($property): void {
            $this->$property = $value;
        })->call($object, $value);
    }
}
