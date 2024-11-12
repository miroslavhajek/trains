<?php declare(strict_types=1);

namespace App\Controller\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\DeviceFactory;
use App\Factory\DeviceLocationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class HubApiControllerTest extends ApiTestCase
{

    use Factories;
    use ResetDatabase;

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
            ->generate('app_hub_api_create_location', ['id' => $device->getId()]);

        $client->request(
            Request::METHOD_POST,
            $url,
            [
                'json' => [
                    'lat' => '12.545454',
                    'lon' => '55.750032',
                    'remoteCreatedAt' => '2024-04-12 13:43:01',
                ],
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $locations = DeviceLocationFactory::repository()->findBy([]);

        self::assertCount(1, $locations);
        self::assertSame($device->getId(), $locations[0]->getDevice()?->getId());
    }
}
