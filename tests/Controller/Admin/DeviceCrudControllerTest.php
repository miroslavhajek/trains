<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Device;
use App\Factory\DeviceFactory;
use App\Factory\UserFactory;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class DeviceCrudControllerTest extends AbstractCrudTestCase
{

    use ResetDatabase;
    use Factories;

    protected function getControllerFqcn(): string
    {
        return DeviceCrudController::class;
    }


    protected function getDashboardFqcn(): string
    {
        return DashboardController::class;
    }


    public function testIndex(): void
    {
        $admin = UserFactory::createAdmin();

        DeviceFactory::createOne();

        $crawler = $this->client
            ->loginUser($admin)
            ->request('GET', $this->generateIndexUrl());

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSame(1, $crawler->filter('tr[data-id]')->count());
    }


    public function testDetail(): void
    {
        $admin = UserFactory::createAdmin();

        $device = DeviceFactory::createOne();

        $this->client
            ->loginUser($admin)
            ->request('GET', $this->generateDetailUrl($device->getIdStrict()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }


    public function testEditForm(): void
    {
        $admin = UserFactory::createAdmin();

        $device = DeviceFactory::createOne();

        $this->client
            ->loginUser($admin)
            ->request('GET', $this->generateEditFormUrl($device->getIdStrict()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }


    public function testEdit(): void
    {
        $admin = UserFactory::createAdmin();

        $device = DeviceFactory::createOne();

        $crawler = $this->client
            ->loginUser($admin)
            ->request('GET', $this->generateEditFormUrl($device->getIdStrict()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter('button[type="submit"]')->form();
        $this->client->followRedirects();
        $this->client->submit($form, [
            'Device[name]' => 'New name',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $updatedDevice = DeviceFactory::repository()->find($device->getId());

        self::assertInstanceOf(Device::class, $updatedDevice);
        self::assertSame('New name', $updatedDevice->getName());
    }
}
