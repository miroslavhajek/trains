<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\RemoteHub;
use App\Entity\RemoteLocation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\ByteString;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class RemoteService
{

    public function __construct(
        private RemoteApi $remoteApi,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
    ) {
    }


    public function initializeDevice(): RemoteHub
    {
        $remoteName = ByteString::fromRandom(12)->toString();

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
