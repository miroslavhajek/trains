<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Device;
use App\Message\RemoteGpsReceivedMessage;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class HubApiController extends AbstractController
{
    #[Route('/api/devices', methods: ['POST'], name: 'app_hub_api_create_device')]
    public function createDevice(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
    ): Response {
        $device = $serializer->deserialize(
            $request->getContent(),
            Device::class,
            'json',
            [AbstractNormalizer::ATTRIBUTES => ['name']],
        );

        $entityManager->persist($device);

        try {
            $entityManager->flush();
        } catch (UniqueConstraintViolationException) {
            return new JsonResponse(['error' => 'Name is not unique'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['id' => $device->getId()], Response::HTTP_CREATED);
    }


    #[Route('/api/devices/locations', methods: ['POST'], name: 'app_hub_api_create_location')]
    public function createLocation(
        Request $request,
        SerializerInterface $serializer,
        MessageBusInterface $messageBus,
    ): Response {
        $message = $serializer->deserialize(
            $request->getContent(),
            RemoteGpsReceivedMessage::class,
            'json',
            [AbstractNormalizer::ATTRIBUTES => ['deviceId', 'lat', 'lon', 'createdAt']],
        );

        $messageBus->dispatch($message);

        return new Response(null, Response::HTTP_CREATED);
    }
}
