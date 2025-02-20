<?php

declare(strict_types=1);

namespace App\Entity\Hub;

use App\Repository\Hub\IconRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IconRepository::class)]
#[UniqueEntity(fields: ['faClass'])]
class Icon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    #[Assert\Regex('/^[a-zA-Z0-9\s\-_]+$/', 'icon.error.invalidFaClass')]
    private ?string $faClass = null;

    public function __toString(): string
    {
        return $this->getLabel() ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getFaClass(): ?string
    {
        return $this->faClass;
    }

    public function setFaClass(?string $faClass): static
    {
        $this->faClass = $faClass;

        return $this;
    }
}
