<?php

namespace App\Entity;

use App\Repository\PathologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PathologyRepository::class)]
class Pathology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pathology.index', 'pathology.show'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Groups(['pathology.index', 'pathology.show'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['pathology.index', 'pathology.show'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\Positive()]
    #[Groups(['pathology.index', 'pathology.show'])]
    private ?int $estimatedRecoveryDays = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['pathology.show'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['pathology.show'])]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, PatientCasePathology>
     */
    #[ORM\OneToMany(targetEntity: PatientCasePathology::class, mappedBy: 'pathology')]
    private Collection $patientCasePathologies;

    public function __construct()
    {
        $this->patientCasePathologies = new ArrayCollection();
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

    public function getEstimatedRecoveryDays(): ?int
    {
        return $this->estimatedRecoveryDays;
    }

    public function setEstimatedRecoveryDays(?int $estimatedRecoveryDays): static
    {
        $this->estimatedRecoveryDays = $estimatedRecoveryDays;

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
     * @return Collection<int, PatientCasePathology>
     */
    public function getPatientCasePathologies(): Collection
    {
        return $this->patientCasePathologies;
    }

    public function addPatientCasePathology(PatientCasePathology $patientCasePathology): static
    {
        if (!$this->patientCasePathologies->contains($patientCasePathology)) {
            $this->patientCasePathologies->add($patientCasePathology);
            $patientCasePathology->setPathology($this);
        }

        return $this;
    }

    public function removePatientCasePathology(PatientCasePathology $patientCasePathology): static
    {
        if ($this->patientCasePathologies->removeElement($patientCasePathology)) {
            // set the owning side to null (unless already changed)
            if ($patientCasePathology->getPathology() === $this) {
                $patientCasePathology->setPathology(null);
            }
        }

        return $this;
    }
}
