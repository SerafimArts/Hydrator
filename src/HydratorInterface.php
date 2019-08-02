<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator;

use Rds\Hydrator\Mapper\MapperInterface;

/**
 * Interface HydratorInterface
 */
interface HydratorInterface
{
    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @param MapperInterface $mapper
     * @return HydratorInterface|$this
     */
    public function add(MapperInterface $mapper): self;

    /**
     * @param array $payload
     * @param object|null $target
     * @param object|null $context
     * @return object
     */
    public function hydrate(array $payload, object $target = null, object $context = null): object;

    /**
     * @param object $object
     * @param object|null $context
     * @return array
     */
    public function toArray(object $object, object $context = null): array;
}
