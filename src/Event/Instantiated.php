<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Event;

/**
 * Class Instantiated
 */
class Instantiated extends Event
{
    /**
     * @return \Closure
     */
    public static function fillConstructor(): \Closure
    {
        return static function (Instantiated $e) {
            $target = $e->getTarget();

            if (\method_exists($target, '__construct')) {
                $target->__construct($e->getContext());
            }
        };
    }
}
