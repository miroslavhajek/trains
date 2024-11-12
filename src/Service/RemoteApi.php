<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\RemoteHub;
use App\Entity\RemoteLocation;
use App\Util\Json;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\String\ByteString;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function sprintf;

class RemoteApi
{
    /**
     * @var array<string, string>
     */
    private static array $headers = [
        'Content-Type' => 'application/json; charset=utf-8',
        'Accept' => 'application/json',
    ];

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EntityManagerInterface $entityManager,
        #[Autowire(env: 'APP_HUB_URL')]
        private readonly string $hubUrl,
    ) {
    }


    public function connect(): RemoteHub
    {
        $remoteName = ByteString::fromRandom(12)->toString();

        $response = $this->client->request(
            'POST',
            sprintf('%s/api/devices', $this->hubUrl),
            [
                'headers' => self::$headers,
                'body' => Json::encode(['name' => $remoteName]),
            ],
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new RuntimeException('Connect HUB failed', $statusCode);
        }

        $remoteId = $response->toArray()['id'];

        $hub = new RemoteHub();
        $hub->setRemoteName($remoteName);
        $hub->setRemoteId(Uuid::fromString($remoteId));

        $this->entityManager->persist($hub);
        $this->entityManager->flush();

        return $hub;
    }


    public function syncLocation(RemoteHub $hub, RemoteLocation $location): void
    {
        $response = $this->client->request(
            'POST',
            sprintf('%s/api/devices/%s/locations', $this->hubUrl, $hub->getRemoteId()),
            [
                'headers' => self::$headers,
                'body' => Json::encode([
                    'lat' => $location->getLat(),
                    'lon' => $location->getLon(),
                ]),
            ],
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new RuntimeException(sprintf('Sync location "%d" failed', $location->getId()), $statusCode);
        }
    }
}
