<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[UniqueEntity('name', message: 'Ce titre existe déjà')]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getCategoriesFull', 'getCourses'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Veuillez saisir un titre pour ce cours!')]
    #[Assert\Length(
        min: 2,
        max: 180,
        minMessage: 'Le titre du cours devra avoir au moins 2 caractères',
        maxMessage: 'Le titre du cours devra avoir maximum 180 caractères'
    )]
    #[Groups(['getCategoriesFull', 'getCourses'])]
    private ?string $name = null;

    #[Assert\Length(
        min: 10,
        max: 2000,
        minMessage: 'Le contenu du cours devra avoir au moins 10 caractères',
        maxMessage: 'Le contenu du cours devra avoir maximum 2000 caractères'
    )]
    #[ORM\Column(type:Types::TEXT, nullable: true)]
    #[Groups(['getCategoriesFull', 'getCourses'])]
    private ?string $content = null;

    #[Assert\Range(notInRangeMessage: 'Le cours devrait durer entre 1 et 50 jours', min: 1, max: 50)]
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['getCategoriesFull', 'getCourses'])]
    #[Assert\NotBlank(message: 'Veuillez saisir une duration pour ce cours!')]
    private ?int $duration = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['getCategoriesFull', 'getCourses'])]
    private ?bool $published = null;

    #[ORM\Column]
    #[Groups(['getCategoriesFull', 'getCourses'])]
    private ?\DateTimeImmutable $dateCreated = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateModified = null;

    #[ORM\Column(length: 180, nullable: true)]
    #[Groups(['getCategoriesFull', 'getCourses'])]
    private ?string $filename = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?Category $category = null;

    /**
     * @var Collection<int, Trainer>
     */
    #[ORM\ManyToMany(targetEntity: Trainer::class, inversedBy: 'courses')]
    private Collection $trainers;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?User $user = null;

    public function __construct()
    {
        $this->published = false;
        $this->setDateCreated(new \DateTimeImmutable());
        $this->trainers = new ArrayCollection();
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

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Trainer>
     */
    public function getTrainers(): Collection
    {
        return $this->trainers;
    }

    public function addTrainer(Trainer $trainer): static
    {
        if (!$this->trainers->contains($trainer)) {
            $this->trainers->add($trainer);
            $trainer->addCourse($this);
        }

        return $this;
    }

    public function removeTrainer(Trainer $trainer): static
    {
        if ($this->trainers->removeElement($trainer)) {
            $trainer->removeCourse($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
