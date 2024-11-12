<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\DeviceRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
class Device
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true, length: 36)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?string $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, DeviceLocation>
     */
    #[ORM\OneToMany(targetEntity: DeviceLocation::class, mappedBy: 'device', orphanRemoval: true)]
    private Collection $locations;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    private bool $connected = true;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->locations = new ArrayCollection();
    }


    public function getId(): ?string
    {
        return $this->id;
    }


    public function getName(): ?string
    {
        return $this->name;
    }


    public function setName(string $name): static
    {
        $this->name = $name;

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


    public function isConnected(): bool
    {
        return $this->connected;
    }


    public function setConnected(bool $connected): static
    {
        $this->connected = $connected;

        return $this;
    }


    /**
     * @return Collection<int, DeviceLocation>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }


    public function addLocation(DeviceLocation $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setDevice($this);
        }

        return $this;
    }
}
