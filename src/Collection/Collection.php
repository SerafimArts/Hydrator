<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Collection;

use Rds\Hydrator\Mapper\MapperInterface;
use Rds\Hydrator\Mapper\AccessorInterface;

/**
 * Class Collection
 */
abstract class Collection implements CollectionInterface
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
     * @return CollectionInterface|$this
     */
    public function add(MapperInterface $element): CollectionInterface
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
