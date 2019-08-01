<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

/*
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rds\Hydrator\Tests;

use Rds\Hydrator\Factory;
use PHPUnit\Framework\Exception;
use Rds\Hydrator\FactoryInterface;
use Rds\Hydrator\HydratorInterface;
use Rds\Hydrator\Loader\JsonLoader;
use Rds\Hydrator\Loader\YamlLoader;
use Rds\Hydrator\Tests\Models\User;
use Rds\Hydrator\Loader\Json5Loader;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class ComplexTestCase
 */
class ComplexTestCase extends TestCase
{
    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testUserIsLoadable(): void
    {
        $hydrator = $this->hydrator(User::class);

        $user = $hydrator->make([
            'id'       => 42,
            'username' => 'Vasya',
            'link'     => 'https://example.com',
            'some'     => [
                'any' => 'test',
            ],
        ]);

        dump($user, $hydrator->toArray($user));

        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @param string $class
     * @return HydratorInterface
     */
    private function hydrator(string $class): HydratorInterface
    {
        return $this->factory()->create($class);
    }

    /**
     * @return FactoryInterface
     */
    private function factory(): FactoryInterface
    {
        return new Factory(
            new YamlLoader(__DIR__ . '/config'),
            new Json5Loader(__DIR__ . '/config'),
            new JsonLoader(__DIR__ . '/config')
        );
    }
}
