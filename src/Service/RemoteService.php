<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\RemoteHub;
use App\Entity\RemoteLocation;
use App\Repository\RemoteHubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\ByteString;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class RemoteService
{

    public function __construct(
        private RemoteApi $remoteApi,
        private ValidatorInterface $validator,
        private RemoteHubRepository $remoteHubRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }


    public function initializeDevice(): RemoteHub
    {
        $hub = $this->remoteHubRepository->findHubSettings();
        if ($hub === null) {
            $remoteName = ByteString::fromRandom(12)->toString();
        } else {
            $remoteName = $hub->getRemoteName();
        }

        $remoteId = $this->remoteApi->connect($remoteName);

        $hub = new RemoteHub(Uuid::fromString($remoteId), $remoteName);

        $this->validator->validate($hub);

        $this->entityManager->persist($hub);
        $this->entityManager->flush();

        return $hub;
    }


    public function sendLocation(RemoteHub $hub, RemoteLocation $location): void
    {
        $this->remoteApi->syncLocation($hub->getRemoteId(), $location);
    }
}
