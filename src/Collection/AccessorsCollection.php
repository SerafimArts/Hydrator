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
use Rds\Hydrator\Mapper\Payload\PayloadInterface;

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
     * @param PayloadInterface $payload
     * @return void
     */
    public function apply(object $instance, PayloadInterface $payload): void
    {
        foreach ($this->getIterator() as $reader) {
            $reader->read($instance, $payload);
        }
    }
}
