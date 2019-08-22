<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Store;

use Rds\Hydrator\Mapper\MapperInterface;
use Rds\Hydrator\Mapper\AccessorInterface;

/**
 * Class Collection
 */
abstract class Store implements StoreInterface
{
    /**
     * @var \SplObjectStorage|AccessorInterface[]
     */
    private $items;

    /**
     * WritersCollection constructor.
     */
    public function __construct()
    {
        $this->items = new \SplObjectStorage();
    }

    /**
     * @param MapperInterface $element
     * @return StoreInterface|$this
     */
    public function add(MapperInterface $element): StoreInterface
    {
        $this->items->attach($element);

        return $this;
    }

    /**
     * @return \Generator|\Traversable
     */
    public function getIterator(): \Traversable
    {
        yield from $this->items;
    }
}
