<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\DeviceLocation;
use App\Entity\RemoteLocationState;
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
            'device' => DeviceFactory::new(),
            'lat' => self::faker()->text(32),
            'lon' => self::faker()->text(32),
            'state' => RemoteLocationState::New,
            'createdAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'remoteCreatedAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }
}
