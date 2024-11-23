<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{

    public static function class(): string
    {
        return User::class;
    }


    public static function createAdmin(): User
    {
        return self::createOne([
            'roles' => ['ROLE_ADMIN'],
        ]);
    }


    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->userName() . '@trains.com',
            'password' => '***',
            'roles' => [],
        ];
    }
}
