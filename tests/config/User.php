<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Rds\Hydrator\Mapper\Attribute;
use Rds\Hydrator\Tests\Models\User;
use Rds\Hydrator\Tests\Models\Avatar;

return [
    User::class => [
        'attributes'     => [
            'attr' => [
                'a'   => 'id',
                'b.b' => 'username',
                'c'   => 'link',
                'd.d' => 'some.any',
            ],
        ],
        'fields'         => [
            'id'    => 'id',
            'login' => 'username',
        ],
        'embedded'       => [
            'avatar' => Avatar::class,
        ],
    ],
];
