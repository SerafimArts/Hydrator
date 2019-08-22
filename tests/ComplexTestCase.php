<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Tests;

use Rds\Hydrator\Hydrator;
use Rds\Hydrator\Mapper\Property;
use Rds\Hydrator\HydratorInterface;
use Rds\Hydrator\Tests\Models\User;
use Rds\Hydrator\Mapper\Embeddable;
use Rds\Hydrator\Tests\Models\Avatar;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class ComplexTestCase
 */
class ComplexTestCase extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testMutators(): void
    {
        $object = $this->createHydrator()->hydrate($this->payload());

        $haystack = [
            'id'     => 42,
            'login'  => 'Vasya',
            'avatar' => [
                'url'     => 'https://example.com',
                'example' => 23,
            ],
        ];

        $this->assertEquals($haystack, \json_decode(\json_encode($object), true));
    }

    /**
     * @return HydratorInterface
     */
    public function createHydrator(): HydratorInterface
    {
        $avatar = new Hydrator(Avatar::class);
        $avatar->add(new Property('url', 'link'));
        $avatar->add(new Property('example', 'some.any'));

        $user = new Hydrator(User::class);
        $user->add(new Property('id'));
        $user->add(new Property('login', 'username'));
        $user->add(new Embeddable('avatar', $avatar));

        return $user;
    }

    /**
     * @return array
     */
    private function payload(): array
    {
        return [
            'id'       => 42,
            'username' => 'Vasya',
            'link'     => 'https://example.com',
            'some'     => [
                'any' => 23,
            ],
        ];
    }
}
