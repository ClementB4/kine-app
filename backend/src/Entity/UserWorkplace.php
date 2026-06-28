<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\UserWorkplaceRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserWorkplaceRepository::class)]
class UserWorkplace
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isActive = null;

    #[ORM\ManyToOne(inversedBy: 'userWorkplaces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userWorkplaces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Workplace $workplace = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getWorkplace(): ?Workplace
    {
        return $this->workplace;
    }

    public function setWorkplace(?Workplace $workplace): static
    {
        $this->workplace = $workplace;

        return $this;
    }
}
