<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Loader;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Rds\Hydrator\Loader\Configurator\ConfiguratorInterface;

/**
 * Class CachedLoader
 */
class CachedLoader implements LoaderInterface
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var LoaderInterface
     */
    private $parent;

    /**
     * @var null
     */
    private $ttl;

    /**
     * @var string
     */
    private $prefix;

    /**
     * CachedLoader constructor.
     *
     * @param CacheInterface $cache
     * @param LoaderInterface $parent
     * @param null $ttl
     * @throws \Exception
     */
    public function __construct(CacheInterface $cache, LoaderInterface $parent, $ttl = null)
    {
        $this->cache = $cache;
        $this->parent = $parent;
        $this->ttl = $ttl;
        $this->prefix = \hash('sha1', \random_bytes(32));
    }

    /**
     * @param ConfiguratorInterface $configurator
     * @param string $class
     * @return iterable|null
     * @throws InvalidArgumentException
     */
    public function load(ConfiguratorInterface $configurator, string $class): ?iterable
    {
        $key = $this->prefix . ':' . $class;

        if (! $this->cache->has($key)) {
            $data = $this->parent->load($configurator, $class);

            $this->cache->set($key, $data, $this->ttl);
        }

        return $this->cache->get($key);
    }
}
