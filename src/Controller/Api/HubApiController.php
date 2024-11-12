<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Device;
use App\Entity\DeviceLocation;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class HubApiController extends AbstractController
{
    #[Route('/api/devices', methods: ['POST'])]
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

        return new JsonResponse(['id' => $device->getId()]);
    }


    #[Route('/api/devices/{id}/locations', methods: ['POST'])]
    public function createLocation(
        Request $request,
        Device $device,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
    ): Response {
        $location = $serializer->deserialize(
            $request->getContent(),
            DeviceLocation::class,
            'json',
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['id', 'device', 'state']],
        );

        $location->setDevice($device);



        $entityManager->persist($device);

        try {
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new BadRequestHttpException('Name is not unique', $e);
        }

        return new Response();
    }
}
