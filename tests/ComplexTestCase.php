<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Tests;

use Rds\Hydrator\Factory;
use PHPUnit\Framework\Exception;
use Rds\Hydrator\FactoryInterface;
use Rds\Hydrator\Tests\Models\User;
use Rds\Hydrator\Tests\Models\Avatar;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class ComplexTestCase
 */
class ComplexTestCase extends TestCase
{
    /**
     * @dataProvider factoryDataProvider
     *
     * @param FactoryInterface $factory
     * @param array $payload
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testUserIsConfigurable(FactoryInterface $factory, array $payload): void
    {
        $user = $factory->create(User::class)->hydrate($payload);

        $avatar = $this->access($user, 'avatar');

        $this->assertInstanceOf(User::class, $user);
        $this->assertInstanceOf(Avatar::class, $avatar);

        $this->assertSame(42, $this->access($user, 'id'));
        $this->assertSame('Vasya', $this->access($user, 'login'));
        $this->assertSame([
            'a' => 42,
            'b' => ['b' => 'Vasya'],
            'c' => 'https://example.com',
            'd' => ['d' => 23],
        ], $this->access($user, 'attr'));

        $this->assertSame('https://example.com', $this->access($avatar, 'url'));
        $this->assertSame(23, $this->access($avatar, 'example'));
    }

    /**
     * @dataProvider factoryDataProvider
     *
     * @param FactoryInterface $factory
     * @param array $payload
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testUserIsSerializable(FactoryInterface $factory, array $payload): void
    {
        $hydrator = $factory->create(User::class);

        $this->assertSame($payload, $hydrator->toArray($hydrator->hydrate($payload)));
    }

    /**
     * @dataProvider factoryDataProvider
     *
     * @param FactoryInterface $factory
     * @param array $payload
     * @return void
     */
    public function testDump(FactoryInterface $factory, array $payload): void
    {
        $this->expectNotToPerformAssertions();

        $hydrator = $factory->create(User::class);

        \dump($hydrator->hydrate($payload));
    }

    /**
     * @return array
     */
    public function factoryDataProvider(): array
    {
        $result = [];

        foreach ($this->loaders(__DIR__ . '/config') as $loader) {
            $factory = new Factory($this->dispatcher());

            $result[\get_class($loader)] = [
                $factory->withLoader($loader),
                $this->payload(),
            ];
        }

        return $result;
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
