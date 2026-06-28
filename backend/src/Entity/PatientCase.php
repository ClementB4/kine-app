<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Enum\PatientCaseStatus;
use App\Repository\PatientCaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PatientCaseRepository::class)]
class PatientCase
{
    use TimestampableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'patientCases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    #[ORM\Column(enumType: PatientCaseStatus::class)]
    private ?PatientCaseStatus $status = null;

    /**
     * @var Collection<int, PatientCasePhysio>
     */
    #[ORM\OneToMany(targetEntity: PatientCasePhysio::class, mappedBy: 'patientCase')]
    private Collection $patientCasePhysios;

    /**
     * @var Collection<int, PatientCasePathology>
     */
    #[ORM\OneToMany(targetEntity: PatientCasePathology::class, mappedBy: 'patientCase')]
    private Collection $patientCasePathologies;

    /**
     * @var Collection<int, Session>
     */
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'patientCase')]
    private Collection $sessions;


    public function __construct()
    {
        $this->patientCasePhysios = new ArrayCollection();
        $this->patientCasePathologies = new ArrayCollection();
        $this->sessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): static
    {
        $this->endedAt = $endedAt;

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

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
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
            $patientCasePhysio->setPatientCase($this);
        }

        return $this;
    }

    public function removePatientCasePhysio(PatientCasePhysio $patientCasePhysio): static
    {
        if ($this->patientCasePhysios->removeElement($patientCasePhysio)) {
            // set the owning side to null (unless already changed)
            if ($patientCasePhysio->getPatientCase() === $this) {
                $patientCasePhysio->setPatientCase(null);
            }
        }

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
            $patientCasePathology->setPatientCase($this);
        }

        return $this;
    }

    public function removePatientCasePathology(PatientCasePathology $patientCasePathology): static
    {
        if ($this->patientCasePathologies->removeElement($patientCasePathology)) {
            // set the owning side to null (unless already changed)
            if ($patientCasePathology->getPatientCase() === $this) {
                $patientCasePathology->setPatientCase(null);
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
            $session->setPatientCase($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getPatientCase() === $this) {
                $session->setPatientCase(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?PatientCaseStatus
    {
        return $this->status;
    }

    public function setStatus(PatientCaseStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}
