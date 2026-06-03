<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isActive = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, UserWorkplace>
     */
    #[ORM\OneToMany(targetEntity: UserWorkplace::class, mappedBy: 'user')]
    private Collection $userWorkplaces;

    #[ORM\OneToOne(targetEntity: Patient::class, mappedBy: 'user', cascade: ['persist'])]
    private ?Patient $patient = null;

    /**
     * @var Collection<int, PatientCasePhysio>
     */
    #[ORM\OneToMany(targetEntity: PatientCasePhysio::class, mappedBy: 'user')]
    private Collection $patientCasePhysios;

    /**
     * @var Collection<int, Session>
     */
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'createdBy')]
    private Collection $sessions;

    public function __construct()
    {
        $this->userWorkplaces = new ArrayCollection();
        $this->patientCasePhysios = new ArrayCollection();
        $this->sessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
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

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

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
            $userWorkplace->setUser($this);
        }

        return $this;
    }

    public function removeUserWorkplace(UserWorkplace $userWorkplace): static
    {
        if ($this->userWorkplaces->removeElement($userWorkplace)) {
            // set the owning side to null (unless already changed)
            if ($userWorkplace->getUser() === $this) {
                $userWorkplace->setUser(null);
            }
        }

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        // unset the owning side of the relation if necessary
        if ($patient === null && $this->patient !== null) {
            $this->patient->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($patient !== null && $patient->getUser() !== $this) {
            $patient->setUser($this);
        }

        $this->patient = $patient;

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
            $patientCasePhysio->setUser($this);
        }

        return $this;
    }

    public function removePatientCasePhysio(PatientCasePhysio $patientCasePhysio): static
    {
        if ($this->patientCasePhysios->removeElement($patientCasePhysio)) {
            // set the owning side to null (unless already changed)
            if ($patientCasePhysio->getUser() === $this) {
                $patientCasePhysio->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setCreatedBy($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getCreatedBy() === $this) {
                $session->setCreatedBy(null);
            }
        }

        return $this;
    }
}
