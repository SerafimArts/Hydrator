<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);



namespace Rds\Hydrator\Mapper;

/**
 * Interface ReaderInterface
 */
interface AccessorInterface extends MapperInterface
{
    /**
     * @param object $instance
     * @param array $data
     * @param object $context
     * @return array
     */
    public function read(object $instance, array $data, object $context = null): array;
}
