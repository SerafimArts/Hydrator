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
 * Interface WriterInterface
 */
interface MutatorInterface extends MapperInterface
{
    /**
     * @param object $instance
     * @param PayloadInterface $payload
     * @param object|null $context
     * @return void
     */
    public function write(object $instance, PayloadInterface $payload, object $context = null): void;
}
