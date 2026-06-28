<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\PatientCasePathologyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PatientCasePathologyRepository::class)]
class PatientCasePathology
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'patientCasePathologies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PatientCase $patientCase = null;

    #[ORM\ManyToOne(inversedBy: 'patientCasePathologies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pathology $pathology = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatientCase(): ?PatientCase
    {
        return $this->patientCase;
    }

    public function setPatientCase(?PatientCase $patientCase): static
    {
        $this->patientCase = $patientCase;

        return $this;
    }

    public function getPathology(): ?Pathology
    {
        return $this->pathology;
    }

    public function setPathology(?Pathology $pathology): static
    {
        $this->pathology = $pathology;

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
}
