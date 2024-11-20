<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{

    public static function class(): string
    {
        return User::class;
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
