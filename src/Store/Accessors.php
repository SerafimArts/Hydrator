<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Store;

use Rds\Hydrator\Mapper\AccessorInterface;
use Rds\Hydrator\Mapper\Payload\PayloadInterface;

/**
 * Class Accessors
 *
 * @method AccessorInterface[] getIterator()
 */
final class Accessors extends Store
{
    /**
     * @param AccessorInterface $accessor
     * @return Accessors|$this
     */
    public function add($accessor): StoreInterface
    {
        \assert($accessor instanceof AccessorInterface);

        return parent::add($accessor);
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
