<?php

declare(strict_types=1);

namespace App\Entity\Account;

use App\Repository\Account\MetaRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MetaRoleRepository::class)]
#[UniqueEntity(fields: ['key'])]
class MetaRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, name: 'key_meta_role')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Assert\Regex('/^[A-Z0-9_]+$/', 'metarole.error.invalidKey')]
    private ?string $key = null;

    #[ORM\Column(length: 2048)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'metaRoles')]
    private Collection $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getKey();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): static
    {
        $this->key = $key;

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

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        $this->roles->removeElement($role);

        return $this;
    }
}
