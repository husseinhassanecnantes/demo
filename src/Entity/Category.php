<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Model\CategoryDTO;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'getCategoriesFull']),
        new GetCollection(normalizationContext: ['groups' => 'getCategories']),
        new Post(denormalizationContext: ['groups' => 'postCategory'])
    ]
)]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity('name', message: 'Cette catégorie existe déjà')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getCategories', 'getCategoriesFull'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Veuillez saisir une catégorie!')]
    #[Assert\Length(
        min: 2,
        max: 180,
        minMessage: 'La catégorie devrait avoir au moins 2 caractères',
        maxMessage: 'La catégorie devrait avoir maximum 180 caractères'
    )]
    #[Groups(['getCategories', 'getCategoriesFull', 'postCategory'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['getCategories', 'getCategoriesFull'])]
    private ?\DateTimeImmutable $dateCreated = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateUpdate = null;

    /**
     * @var Collection<int, Course>
     */
    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'category')]
    #[Groups(['getCategoriesFull'])]
    private Collection $courses;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->dateCreated = new \DateTimeImmutable();
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

    public function getDateCreated(): ?\DateTimeImmutable
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeImmutable $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeImmutable
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(?\DateTimeImmutable $dateUpdate): static
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setCategory($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getCategory() === $this) {
                $course->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public static function createfromDTO(CategoryDTO $dto): self
    {
        $category = new self();
        $category->setName($dto->name);
        return $category;
    }

}
