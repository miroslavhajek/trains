<?php declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Device;
use App\Entity\DeviceLocation;
use App\Message\RemoteGpsReceivedMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RemoteGpsReceivedMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }


    public function __invoke(RemoteGpsReceivedMessage $message): void
    {
        /** @var Device $device */
        $device = $this->entityManager->getReference(Device::class, $message->deviceId);

        $location = new DeviceLocation();
        $location->setDevice($device);
        $location->setLat($message->lat);
        $location->setLon($message->lon);
        $location->setRemoteCreatedAt($message->remoteCreatedAt);

        $this->entityManager->persist($location);
        $this->entityManager->flush();
    }
}
