<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\RemoteHub;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function sprintf;

readonly class RemoteApi
{
    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
        #[Autowire(env: 'APP_HUB_URL')]
        private string $hubUrl,
    ) {
    }


    public function connect(): RemoteHub
    {
        $response = $this->client->request(
            'POST',
            sprintf('%s/api/devices', $this->hubUrl),
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new LogicException('Connect failed');
        }

        $remoteId = $response->toArray()['id'];

        $hub = new RemoteHub();
        $hub->setRemoteId($remoteId);

        $this->entityManager->persist($hub);

        return $hub;
    }

    public function syncLocation(RemoteHub $hub): void
    {
        $response = $this->client->request(
            'POST',
            sprintf('%s/api/devices/%s/locations', $this->hubUrl, $hub->getRemoteId()),
        );
    }
}
