<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Enum\ExerciseCategory;
use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['exercise.index', 'exercise.show'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 5)]
    #[Groups(['exercise.index', 'exercise.show'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['exercise.index', 'exercise.show'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url()]
    #[Assert\Regex('/youtube\.com|youtu\.be/')]
    #[Groups(['exercise.show'])]
    private ?string $videoUrl = null;

    /**
     * @var Collection<int, SessionExercise>
     */
    #[ORM\OneToMany(targetEntity: SessionExercise::class, mappedBy: 'exercise')]
    private Collection $sessionExercises;

    #[ORM\Column(enumType: ExerciseCategory::class)]
    #[Assert\NotBlank()]
    #[Groups(['exercise.index', 'exercise.show'])]
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
