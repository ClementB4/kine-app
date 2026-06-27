<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['patient.index', 'patient.show'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['patient.index', 'patient.show'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    #[Groups(['patient.index', 'patient.show'])]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['patient.index', 'patient.show'])]
    private ?\DateTimeImmutable $birthDate = null;

    #[ORM\Column(length: 5, nullable: true)]
    #[Groups(['patient.index', 'patient.show'])]
    private ?string $gender = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['patient.show'])]
    private ?int $height = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['patient.show'])]
    private ?int $weight = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['patient.show'])]
    private ?string $job = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['patient.show'])]
    private ?string $sport = null;

    #[ORM\Column(length: 6, nullable: true)]
    #[Groups(['patient.show'])]
    private ?string $laterality = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['patient.show'])]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['patient.show'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['patient.show'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(inversedBy: 'patient', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['patient.index', 'patient.show'])]
    private ?User $user = null;

    /**
     * @var Collection<int, PatientCase>
     */
    #[ORM\OneToMany(targetEntity: PatientCase::class, mappedBy: 'patient')]
    private Collection $patientCases;

    public function __construct()
    {
        $this->patientCases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeImmutable $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getSport(): ?string
    {
        return $this->sport;
    }

    public function setSport(?string $sport): static
    {
        $this->sport = $sport;

        return $this;
    }

    public function getLaterality(): ?string
    {
        return $this->laterality;
    }

    public function setLaterality(?string $laterality): static
    {
        $this->laterality = $laterality;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, PatientCase>
     */
    public function getPatientCases(): Collection
    {
        return $this->patientCases;
    }

    public function addPatientCase(PatientCase $patientCase): static
    {
        if (!$this->patientCases->contains($patientCase)) {
            $this->patientCases->add($patientCase);
            $patientCase->setPatient($this);
        }

        return $this;
    }

    public function removePatientCase(PatientCase $patientCase): static
    {
        if ($this->patientCases->removeElement($patientCase)) {
            // set the owning side to null (unless already changed)
            if ($patientCase->getPatient() === $this) {
                $patientCase->setPatient(null);
            }
        }

        return $this;
    }
}
