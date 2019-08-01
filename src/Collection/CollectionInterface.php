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

namespace Rds\Hydrator\Collection;

use Rds\Hydrator\Mapper\MapperInterface;

/**
 * Interface CollectionInterface
 */
interface CollectionInterface extends \IteratorAggregate
{
    /**
     * @param MapperInterface $element
     * @return CollectionInterface|$this
     */
    public function add(MapperInterface $element): self;

    /**
     * @param object $instance
     * @param array $data
     * @return mixed
     */
    public function apply(object $instance, array $data);
}
