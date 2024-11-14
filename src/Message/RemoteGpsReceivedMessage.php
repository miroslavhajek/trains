<?php declare(strict_types=1);

namespace App\Message;

use DateTimeImmutable;

final readonly class RemoteGpsReceivedMessage
{

    public function __construct(
        public string $deviceId,
        public string $lat,
        public string $lon,
        public DateTimeImmutable $remoteCreatedAt,
    ) {
    }
}
