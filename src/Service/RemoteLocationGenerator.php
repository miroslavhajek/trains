<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\RemoteLocation;
use App\Repository\RemoteLocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use function random_int;

readonly class RemoteLocationGenerator
{
    public function __construct(
        private RemoteLocationRepository $remoteLocationRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }


    public function generate(float $startLat, float $startLon): RemoteLocation
    {
        $newLocation = new RemoteLocation();
        $newLocation->setLat($startLat);
        $newLocation->setLon($startLon);

        $lastLocation = $this->remoteLocationRepository->findOneBy([], ['createdAt' => 'DESC']);
        if ($lastLocation !== null) {
            $latRand = random_int(-2, 10) / 1000;
            $lonRand = random_int(-2, 10) / 1000;

            $newLocation->setLat($lastLocation->getLatAsFloat() + $latRand);
            $newLocation->setLon($lastLocation->getLonAsFloat() + $lonRand);
        }

        $this->entityManager->persist($newLocation);
        $this->entityManager->flush();

        return $newLocation;
    }
}
