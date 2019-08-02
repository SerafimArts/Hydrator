<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Event;

use Rds\Hydrator\Mapper\Payload\PayloadInterface;

/**
 * Interface EventInterface
 */
interface EventInterface
{
    /**
     * @return object
     */
    public function getTarget(): object;

    /**
     * @return PayloadInterface
     */
    public function getPayload(): PayloadInterface;

    /**
     * @return object|null
     */
    public function getContext(): ?object;
}
