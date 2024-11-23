<?php declare(strict_types=1);

namespace App\Admin\Dto;

use DateTimeImmutable;

final class DeviceListItem
{

    private ?DateTimeImmutable $lastOnlineAt;

    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly DateTimeImmutable $createdAt,
        ?string $lastOnlineAt,
    ) {
        $this->lastOnlineAt = $lastOnlineAt !== null ? new DateTimeImmutable($lastOnlineAt) : null;
    }


    public function getId(): string
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }


    public function getLastOnlineAt(): ?DateTimeImmutable
    {
        return $this->lastOnlineAt;
    }


    public function isOnline(): bool
    {
        $threshold = (new DateTimeImmutable())->modify('-10 seconds');

        return $this->getLastOnlineAt() > $threshold;
    }
}
