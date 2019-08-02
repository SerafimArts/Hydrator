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
 * Class Avatar
 */
class Avatar
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $example;

    /**
     * @var User
     */
    private $user;

    /**
     * Avatar constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
