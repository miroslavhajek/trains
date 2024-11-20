<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\PageFactory;
use App\Factory\UserFactory;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
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

        PageFactory::createMany(36, [
            'publishedAt' => new DateTimeImmutable(),
        ]);
    }
}
