<?php

namespace App\Entity;

use App\Enum\ExerciseCategory;
use App\Enum\ExerciseType;
use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videoUrl = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, SessionExercise>
     */
    #[ORM\OneToMany(targetEntity: SessionExercise::class, mappedBy: 'exercise')]
    private Collection $sessionExercises;

    #[ORM\Column(enumType: ExerciseCategory::class)]
    private ?ExerciseCategory $category = null;

    public function __construct()
    {
        $this->sessionExercises = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function setVideoUrl(?string $videoUrl): static
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, SessionExercise>
     */
    public function getSessionExercises(): Collection
    {
        return $this->sessionExercises;
    }

    public function addSessionExercise(SessionExercise $sessionExercise): static
    {
        if (!$this->sessionExercises->contains($sessionExercise)) {
            $this->sessionExercises->add($sessionExercise);
            $sessionExercise->setExercise($this);
        }

        return $this;
    }

    public function removeSessionExercise(SessionExercise $sessionExercise): static
    {
        if ($this->sessionExercises->removeElement($sessionExercise)) {
            // set the owning side to null (unless already changed)
            if ($sessionExercise->getExercise() === $this) {
                $sessionExercise->setExercise(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?ExerciseCategory
    {
        return $this->category;
    }

    public function setCategory(ExerciseCategory $category): static
    {
        $this->category = $category;

        return $this;
    }
}
