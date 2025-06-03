<?php

declare(strict_types=1);

namespace App\Entity\Hub;

use App\Entity\Account\User;
use App\Repository\Hub\RightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Right is described by a role and a description.
 * It is always associated with an Appli, and for a given Appli, each right MUST have a unique role.
 */
#[ORM\Entity(repositoryClass: RightRepository::class)]
#[UniqueEntity(fields: ['appli', 'role'])]
#[ORM\UniqueConstraint(fields: ['appli', 'role'])]
#[ORM\Table(name: 'right2')]
class Right
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Assert\Regex('/^[A-Z0-9_]+$/', 'right.error.invalidRole')]
    private ?string $role = null;

    #[ORM\Column(length: 2048)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'rights')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appli $appli = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'rights')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getRole() . ' : ' . $this->getDescription() ?? '';
    }

    public function fullString(): string
    {
        return '<strong>' . $this->getAppli()->getTitle() . '</strong> - ' . $this->__toString();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

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

    public function getAppli(): ?Appli
    {
        return $this->appli;
    }

    public function setAppli(?Appli $appli): static
    {
        $this->appli = $appli;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addRight($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeRight($this);
        }

        return $this;
    }
}
