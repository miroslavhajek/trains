<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\Device;
use DateTimeImmutable;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Device>
 */
final class DeviceFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Device::class;
    }


    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->unique()->name,
            'createdAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }
}
