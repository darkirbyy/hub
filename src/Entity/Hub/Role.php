<?php

declare(strict_types=1);

namespace App\Entity\Hub;

use App\Repository\Hub\RoleRepository;
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

    public function __construct()
    {
    }

    public function __toString(): string
    {
        return $this->getKey() . ' : ' . $this->getDescription() ?? '';
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
}
