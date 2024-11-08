<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\RemoteLocationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RemoteLocationRepository::class)]
class RemoteLocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $lat = null;

    #[ORM\Column(length: 32)]
    private ?string $lon = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(length: 64, enumType: RemoteLocationState::class)]
    private RemoteLocationState $state = RemoteLocationState::New;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getLat(): ?string
    {
        return $this->lat;
    }


    public function setLat(string $lat): static
    {
        $this->lat = $lat;

        return $this;
    }


    public function getLon(): ?string
    {
        return $this->lon;
    }


    public function setLon(string $lon): static
    {
        $this->lon = $lon;

        return $this;
    }


    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }


    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    public function getState(): RemoteLocationState
    {
        return $this->state;
    }


    public function setState(RemoteLocationState $state): static
    {
        $this->state = $state;

        return $this;
    }
}
