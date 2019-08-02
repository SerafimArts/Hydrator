<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Mapper;

use Rds\Hydrator\Mapper\Payload\PayloadInterface;

/**
 * Interface ReaderInterface
 */
interface AccessorInterface extends MapperInterface
{
    /**
     * @param object $instance
     * @param PayloadInterface $payload
     * @param object $context
     * @return void
     */
    public function read(object $instance, PayloadInterface $payload, object $context = null): void;
}
