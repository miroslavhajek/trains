<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\DeviceLocation;
use DateTimeImmutable;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<DeviceLocation>
 */
final class DeviceLocationFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return DeviceLocation::class;
    }


    protected function defaults(): array|callable
    {
        return [
            'device' => DeviceFactory::findOrCreate([]),
            'lat' => self::faker()->latitude,
            'lon' => self::faker()->longitude,
            'createdAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'remoteCreatedAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }
}
