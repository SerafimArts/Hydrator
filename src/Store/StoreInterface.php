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
use Rds\Hydrator\Mapper\Payload\PayloadInterface;

/**
 * Interface StoreInterface
 */
interface StoreInterface extends \IteratorAggregate
{
    /**
     * @param MapperInterface $element
     * @return StoreInterface|$this
     */
    public function add(MapperInterface $element): self;

    /**
     * @param object $instance
     * @param PayloadInterface $payload
     * @return void
     */
    public function apply(object $instance, PayloadInterface $payload): void;
}
