<?php declare(strict_types=1);

namespace App\Controller\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\DeviceFactory;
use App\Message\RemoteGpsReceivedMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope; // phpcs:ignore SlevomatCodingStandard.Namespaces.UnusedUses.UnusedUse
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class HubApiControllerTest extends ApiTestCase
{

    use Factories;
    use ResetDatabase;
    use InteractsWithMessenger;

    public function testCreateDevice(): void
    {
        $client = static::createClient();

        $url = static::getContainer()
            ->get(UrlGeneratorInterface::class)
            ->generate('app_hub_api_create_device');

        $client->request(
            Request::METHOD_POST,
            $url,
            ['json' => ['name' => 'Test name']],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $createdDevices = DeviceFactory::repository()->findAll();

        self::assertCount(1, $createdDevices);
        self::assertSame('Test name', $createdDevices[0]->getName());
    }


    public function testCreateDeviceDuplicationFailed(): void
    {
        $client = static::createClient();

        DeviceFactory::createOne(['name' => 'FooBar']);

        $url = static::getContainer()
            ->get(UrlGeneratorInterface::class)
            ->generate('app_hub_api_create_device');

        $client->request(
            Request::METHOD_POST,
            $url,
            ['json' => ['name' => 'FooBar']],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }


    public function testCreateLocation(): void
    {
        $client = static::createClient();

        $device = DeviceFactory::createOne();

        $url = static::getContainer()
            ->get(UrlGeneratorInterface::class)
            ->generate('app_hub_api_create_location');

        $client->request(
            Request::METHOD_POST,
            $url,
            [
                'json' => [
                    'deviceId' => $device->getId(),
                    'lat' => '12.545454',
                    'lon' => '55.750032',
                    'createdAt' => '2024-04-12 13:43:01',
                ],
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        /** @var Envelope[] $envelope */
        $envelope = $this->transport('async')->get();

        self::assertCount(1, $envelope);
        self::assertInstanceOf(RemoteGpsReceivedMessage::class, $envelope[0]->getMessage());
    }
}
