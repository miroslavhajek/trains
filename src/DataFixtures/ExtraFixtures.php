<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\DeviceFactory;
use App\Factory\DeviceLocationFactory;
use App\Factory\PageFactory;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ExtraFixtures extends Fixture implements FixtureGroupInterface
{

    /**
     * @inheritdoc
     */
    public static function getGroups(): array
    {
        return ['extra'];
    }


    public function load(ObjectManager $manager): void
    {
        PageFactory::createMany(36, [
            'publishedAt' => new DateTimeImmutable(),
        ]);

        DeviceFactory::createMany(36, [
            'locations' => DeviceLocationFactory::createMany(100),
        ]);
    }
}
