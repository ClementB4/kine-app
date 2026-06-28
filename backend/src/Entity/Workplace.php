<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\WorkplaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: WorkplaceRepository::class)]
class Workplace
{
    use TimestampableTrait;

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

    /**
     * @var Collection<int, UserWorkplace>
     */
    #[ORM\OneToMany(targetEntity: UserWorkplace::class, mappedBy: 'workplace')]
    private Collection $userWorkplaces;

    /**
     * @var Collection<int, PatientCasePhysio>
     */
    #[ORM\OneToMany(targetEntity: PatientCasePhysio::class, mappedBy: 'workplace')]
    private Collection $patientCasePhysios;

    public function __construct()
    {
        $this->userWorkplaces = new ArrayCollection();
        $this->patientCasePhysios = new ArrayCollection();
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
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;

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
     * @return Collection<int, PatientCasePhysio>
     */
    public function getPatientCasePhysios(): Collection
    {
        return $this->patientCasePhysios;
    }

    public function addPatientCasePhysio(PatientCasePhysio $patientCasePhysio): static
    {
        if (!$this->patientCasePhysios->contains($patientCasePhysio)) {
            $this->patientCasePhysios->add($patientCasePhysio);
            $patientCasePhysio->setWorkplace($this);
        }

        return $this;
    }

    public function removePatientCasePhysio(PatientCasePhysio $patientCasePhysio): static
    {
        if ($this->patientCasePhysios->removeElement($patientCasePhysio)) {
            // set the owning side to null (unless already changed)
            if ($patientCasePhysio->getWorkplace() === $this) {
                $patientCasePhysio->setWorkplace(null);
            }
        }

        return $this;
    }
}
