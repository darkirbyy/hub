<?php

declare(strict_types=1);

namespace App\Entity\Account;

use App\Entity\Hub\Appli;
use App\Repository\Account\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Role is described by a key (name key_role in the db) and a description.
 * It is always associated with an Appli, and for a given Appli, each role MUST have a unique key.
 */
#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[UniqueEntity(fields: ['appli', 'key'])]
#[ORM\UniqueConstraint(fields: ['appli', 'key'])]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, name: 'key_role')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Assert\Regex('/^[A-Z0-9_]+$/', 'role.error.invalidKey')]
    private ?string $key = null;

    #[ORM\Column(length: 2048)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'roles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appli $appli = null;

    #[ORM\ManyToMany(targetEntity: MetaRole::class, mappedBy: 'roles')]
    private Collection $metaRoles;

    public function __construct()
    {
        $this->metaRoles = new ArrayCollection();
    }

    public function __toString(): string
    {
        // return $this->getKey();
        return $this->getKey() . ' (' . $this->getAppli() . ')' ?? '';
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

    public function getAppli(): ?Appli
    {
        return $this->appli;
    }

    public function setAppli(?Appli $appli): static
    {
        $this->appli = $appli;

        return $this;
    }

    public function getMetaRoles(): Collection
    {
        return $this->metaRoles;
    }

    public function addMetaRole(MetaRole $metaRole): static
    {
        if (!$this->metaRoles->contains($metaRole)) {
            $this->metaRoles->add($metaRole);
            $metaRole->addRole($this);
        }

        return $this;
    }

    public function removeMetaRole(MetaRole $metaRole): static
    {
        if ($this->metaRoles->removeElement($metaRole)) {
            $metaRole->removeRole($this);
        }

        return $this;
    }
}
