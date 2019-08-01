<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Collection;

use Rds\Hydrator\Mapper\MutatorInterface;

/**
 * Class ReadersCollection
 *
 * @method MutatorInterface[] getIterator()
 */
final class MutatorsCollection extends Collection
{
    /**
     * @param MutatorInterface $writer
     * @return AccessorsCollection|$this
     */
    public function add($writer): CollectionInterface
    {
        \assert($writer instanceof MutatorInterface);

        return parent::add($writer);
    }

    /**
     * @param object $instance
     * @param array $data
     * @return object
     */
    public function apply(object $instance, array $data): object
    {
        foreach ($this->getIterator() as $reader) {
            $reader->write($instance, $data);
        }

        return $instance;
    }
}
