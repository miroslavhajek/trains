<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\RemoteHubRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RemoteHubRepository::class)]
class RemoteHub
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $remoteId = null;

    #[ORM\Column(length: 255)]
    private ?string $remoteName = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getRemoteId(): ?Uuid
    {
        return $this->remoteId;
    }


    public function setRemoteId(Uuid $remoteId): static
    {
        $this->remoteId = $remoteId;

        return $this;
    }


    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }


    public function getRemoteName(): ?string
    {
        return $this->remoteName;
    }


    public function setRemoteName(string $remoteName): static
    {
        $this->remoteName = $remoteName;

        return $this;
    }
}
