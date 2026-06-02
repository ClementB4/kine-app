<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateImmutable $birthDate = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $height = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $weight = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $job = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $sport = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $laterality = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(inversedBy: 'patient', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    /**
     * @var Collection<int, PatientPhysio>
     */
    #[ORM\OneToMany(targetEntity: PatientPhysio::class, mappedBy: 'patient')]
    private Collection $patientPhysios;

    public function __construct()
    {
        $this->patientPhysios = new ArrayCollection();
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

    public function getBirthDate(): ?\DateImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateImmutable $birthDate): static
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

    public function setJob(string $job): static
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
     * @return Collection<int, PatientPhysio>
     */
    public function getPatientPhysios(): Collection
    {
        return $this->patientPhysios;
    }

    public function addPatientPhysio(PatientPhysio $patientPhysio): static
    {
        if (!$this->patientPhysios->contains($patientPhysio)) {
            $this->patientPhysios->add($patientPhysio);
            $patientPhysio->setPatient($this);
        }

        return $this;
    }

    public function removePatientPhysio(PatientPhysio $patientPhysio): static
    {
        if ($this->patientPhysios->removeElement($patientPhysio)) {
            // set the owning side to null (unless already changed)
            if ($patientPhysio->getPatient() === $this) {
                $patientPhysio->setPatient(null);
            }
        }

        return $this;
    }
}
