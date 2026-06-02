<?php

namespace App\Entity;

use App\Repository\WorkplaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: WorkplaceRepository::class)]
class Workplace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $town = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, UserWorkplace>
     */
    #[ORM\OneToMany(targetEntity: UserWorkplace::class, mappedBy: 'workplace')]
    private Collection $userWorkplaces;

    /**
     * @var Collection<int, PatientPhysio>
     */
    #[ORM\OneToMany(targetEntity: PatientPhysio::class, mappedBy: 'workplace')]
    private Collection $patientPhysios;

    public function __construct()
    {
        $this->userWorkplaces = new ArrayCollection();
        $this->patientPhysios = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): static
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(?string $town): static
    {
        $this->town = $town;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, UserWorkplace>
     */
    public function getUserWorkplaces(): Collection
    {
        return $this->userWorkplaces;
    }

    public function addUserWorkplace(UserWorkplace $userWorkplace): static
    {
        if (!$this->userWorkplaces->contains($userWorkplace)) {
            $this->userWorkplaces->add($userWorkplace);
            $userWorkplace->setWorkplace($this);
        }

        return $this;
    }

    public function removeUserWorkplace(UserWorkplace $userWorkplace): static
    {
        if ($this->userWorkplaces->removeElement($userWorkplace)) {
            // set the owning side to null (unless already changed)
            if ($userWorkplace->getWorkplace() === $this) {
                $userWorkplace->setWorkplace(null);
            }
        }

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
            $patientPhysio->setWorkplace($this);
        }

        return $this;
    }

    public function removePatientPhysio(PatientPhysio $patientPhysio): static
    {
        if ($this->patientPhysios->removeElement($patientPhysio)) {
            // set the owning side to null (unless already changed)
            if ($patientPhysio->getWorkplace() === $this) {
                $patientPhysio->setWorkplace(null);
            }
        }

        return $this;
    }
}
