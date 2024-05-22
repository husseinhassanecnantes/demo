<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[UniqueEntity('name', message: 'Ce titre existe déjà')]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Veuillez saisir un titre pour ce cours!')]
    #[Assert\Length(
        min: 2,
        max: 180,
        minMessage: 'Le titre du cours devra avoir au moins 2 caractères',
        maxMessage: 'Le titre du cours devra avoir maximum 180 caractères'
    )]
    private ?string $name = null;

    #[Assert\Length(
        min: 10,
        max: 2000,
        minMessage: 'Le contenu du cours devra avoir au moins 10 caractères',
        maxMessage: 'Le contenu du cours devra avoir maximum 2000 caractères'
    )]
    #[ORM\Column(type:Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[Assert\Range(notInRangeMessage: 'Le cours devrait durer entre 1 et 50 jours', min: 1, max: 50)]
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $duration;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $published = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreated = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateModified = null;

    public function __construct()
    {
        $this->published = false;
        $this->setDateCreated(new \DateTimeImmutable());
    }


    public function getId(): ?int
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeImmutable
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeImmutable $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateModified(): ?\DateTimeImmutable
    {
        return $this->dateModified;
    }

    public function setDateModified(?\DateTimeImmutable $dateModified): static
    {
        $this->dateModified = $dateModified;

        return $this;
    }
}
