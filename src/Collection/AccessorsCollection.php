<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Collection;

use Rds\Hydrator\Mapper\AccessorInterface;

/**
 * Class ReadersCollection
 *
 * @method AccessorInterface[] getIterator()
 */
final class AccessorsCollection extends Collection
{
    /**
     * @param AccessorInterface $reader
     * @return AccessorsCollection|$this
     */
    public function add($reader): CollectionInterface
    {
        \assert($reader instanceof AccessorInterface);

        return parent::add($reader);
    }

    /**
     * @param object $instance
     * @param array $data
     * @return array
     */
    public function apply(object $instance, array $data): array
    {
        foreach ($this->getIterator() as $reader) {
            $data = $reader->read($instance, $data);
        }

        return $data;
    }
}
