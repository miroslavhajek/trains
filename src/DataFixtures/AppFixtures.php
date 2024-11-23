<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }


    /**
     * @inheritdoc
     */
    public static function getGroups(): array
    {
        return ['app'];
    }


    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'admin@trains.com',
            'password' => $this->passwordHasher->hashPassword(new User(), 'admin'),
            'roles' => [
                'ROLE_ADMIN',
            ],
        ]);
    }
}
