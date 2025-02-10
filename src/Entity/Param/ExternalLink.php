<?php

declare(strict_types=1);

namespace App\Entity\Param;

use App\Repository\Param\ExternalLinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExternalLinkRepository::class)]
class ExternalLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Icon $icon = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 2)]
    private ?string $text = null;

    #[ORM\Column(length: 2048)]
    #[Assert\NotBlank]
    #[Assert\Url(requireTld: true)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'externalLinks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appli $appli = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

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
