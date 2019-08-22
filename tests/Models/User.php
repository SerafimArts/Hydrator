<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Tests\Models;

/**
 * Class User
 */
class User
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $login;

    /**
     * @var Avatar
     */
    public $avatar;
}
