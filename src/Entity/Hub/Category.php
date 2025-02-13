<?php

declare(strict_types=1);

namespace App\Entity\Hub;

use App\Repository\Hub\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity(fields: ['number'])]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $number = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    private ?string $label = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Icon $icon = null;

    #[ORM\OneToMany(targetEntity: Appli::class, mappedBy: 'category')]
    #[ORM\OrderBy(['number' => 'ASC'])]
    private Collection $applis;

    public function __construct()
    {
        $this->applis = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getLabel();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): static
    {
        $this->number = $number;

        return $this;
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

    public function getIcon(): ?Icon
    {
        return $this->icon;
    }

    public function setIcon(?Icon $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getApplis(): Collection
    {
        return $this->applis;
    }

    public function addAppli(Appli $appli): static
    {
        if (!$this->applis->contains($appli)) {
            $this->applis->add($appli);
            $appli->setCategory($this);
        }

        return $this;
    }

    public function removeAppli(Appli $appli): static
    {
        if ($this->applis->removeElement($appli)) {
            // set the owning side to null (unless already changed)
            if ($appli->getCategory() === $this) {
                $appli->setCategory(null);
            }
        }

        return $this;
    }
}
