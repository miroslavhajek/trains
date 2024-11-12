<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\RemoteLocation;
use App\Util\Json;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
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
        #[Autowire(env: 'APP_HUB_URL')]
        private readonly string $hubUrl,
    ) {
    }


    public function connect(string $name): string
    {
        $response = $this->client->request(
            'POST',
            sprintf('%s/api/devices', $this->hubUrl),
            [
                'headers' => self::$headers,
                'body' => Json::encode(['name' => $name]),
            ],
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== Response::HTTP_CREATED) {
            throw new RuntimeException('Connect HUB failed', $statusCode);
        }

        return $response->toArray()['id'];
    }


    public function syncLocation(Uuid $hubId, RemoteLocation $location): void
    {
        $response = $this->client->request(
            'POST',
            sprintf('%s/api/devices/%s/locations', $this->hubUrl, $hubId->toString()),
            [
                'headers' => self::$headers,
                'body' => Json::encode([
                    'lat' => $location->getLat(),
                    'lon' => $location->getLon(),
                    'remoteCreatedAt' => $location->getCreatedAt()->format('Y-m-d H:i:s'),
                ]),
            ],
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== Response::HTTP_CREATED) {
            throw new RuntimeException(sprintf('Sync location "%d" failed', $location->getId()), $statusCode);
        }
    }
}
