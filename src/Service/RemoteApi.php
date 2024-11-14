<?php declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Metadata\UrlGeneratorInterface;
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
        private readonly UrlGeneratorInterface $urlGenerator,
        #[Autowire(env: 'APP_HUB_URL')]
        private readonly string $hubUrl,
    ) {
    }


    public function connect(string $name): string
    {
        $path = $this->urlGenerator->generate('app_hub_api_create_device', [], UrlGeneratorInterface::ABS_PATH);

        $response = $this->client->request(
            'POST',
            $this->hubUrl . $path,
            [
                'max_redirects' => 0,
                'headers' => self::$headers,
                'body' => Json::encode(['name' => $name]),
            ],
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== Response::HTTP_CREATED) {
            throw new RuntimeException(
                sprintf(
                    'Connect HUB failed: %s',
                    $response->getContent(false),
                ),
                $statusCode,
            );
        }

        return $response->toArray()['id'];
    }


    public function syncLocation(Uuid $hubId, RemoteLocation $location): void
    {
        $path = $this->urlGenerator->generate('app_hub_api_create_location', [], UrlGeneratorInterface::ABS_PATH);

        $response = $this->client->request(
            'POST',
            $this->hubUrl . $path,
            [
                'max_redirects' => 0,
                'headers' => self::$headers,
                'body' => Json::encode([
                    'deviceId' => $hubId->toString(),
                    'lat' => $location->getLat(),
                    'lon' => $location->getLon(),
                    'createdAt' => $location->getCreatedAt()->format('Y-m-d H:i:s'),
                ]),
            ],
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== Response::HTTP_CREATED) {
            throw new RuntimeException(
                sprintf(
                    'Sync location "%d" failed: %s',
                    $location->getId(),
                    $response->getContent(false),
                ),
                $statusCode,
            );
        }
    }
}
