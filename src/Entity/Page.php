<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\PageRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[UniqueEntity(fields: ['slug'])]
class Page
{
    private const string SLUG_REGEX = '/^[a-z0-9]+(-[a-z0-9]+)*$/';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(pattern: self::SLUG_REGEX)]
    #[Gedmo\Slug(fields: ['title'], unique: true, updatable: false)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $publishedAt = null;

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


    public function getSlug(): ?string
    {
        return $this->slug;
    }


    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }


    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }


    public function getContent(): ?string
    {
        return $this->content;
    }


    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }


    public function getPublishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }


    public function setPublishedAt(?DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }


    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }


    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
