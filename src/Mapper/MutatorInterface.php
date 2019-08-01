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
 * Interface WriterInterface
 */
interface MutatorInterface extends MapperInterface
{
    /**
     * @param object $instance
     * @param array $data
     * @param object|null $context
     * @return void
     */
    public function write(object $instance, array $data, object $context = null): void;
}
