<?php

declare(strict_types=1);

namespace App\Entity\Hub;

use App\Entity\Account\Role;
use App\Repository\Hub\AppliRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Entity\File as FileMeta;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: AppliRepository::class)]
#[UniqueEntity(fields: ['name'])]
#[UniqueEntity(fields: ['path'])]
#[UniqueEntity(fields: ['number'])]
#[Vich\Uploadable]
class Appli
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 2)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'applis')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Category $category = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $number = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $linkText = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?bool $public = null;

    #[
        Vich\UploadableField(
            mapping: 'applis',
            fileNameProperty: 'imageMeta.name',
            size: 'imageMeta.size',
            mimeType: 'imageMeta.mimeType',
            originalName: 'imageMeta.originalName',
            dimensions: 'imageMeta.dimensions',
        ),
    ]
    #[Assert\When(expression: 'this.getImageUpdatedAt() == null', constraints: [new Assert\NotBlank()])]
    #[Assert\Image(maxSize: '1Mi', minWidth: 128, maxWidth: 1024, minRatio: 0.25, maxRatio: 4, mimeTypes: ['image/jpeg', 'image/png', 'image/webp'], detectCorrupted: true)]
    private ?File $imageFile = null;

    #[ORM\Embedded(class: 'Vich\UploaderBundle\Entity\File')]
    private ?FileMeta $imageMeta = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $imageUpdatedAt = null;

    #[ORM\OneToMany(targetEntity: ExternalLink::class, mappedBy: 'appli', orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    private Collection $externalLinks;

    #[ORM\OneToMany(targetEntity: Role::class, mappedBy: 'appli')]
    private Collection $roles;

    public function __construct()
    {
        $this->public = false;
        $this->imageMeta = new FileMeta();
        $this->externalLinks = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
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

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(?bool $public): static
    {
        $this->public = $public;

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

    public function getLinkText(): ?string
    {
        return $this->linkText;
    }

    public function setLinkText(?string $linkText): static
    {
        $this->linkText = $linkText;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->imageUpdatedAt = new \DateTime();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageMeta(FileMeta $imageMeta): void
    {
        $this->imageMeta = $imageMeta;
    }

    public function getImageMeta(): ?FileMeta
    {
        return $this->imageMeta;
    }

    public function getImageUpdatedAt(): ?\DateTime
    {
        return $this->imageUpdatedAt;
    }

    public function getExternalLinks(): Collection
    {
        return $this->externalLinks;
    }

    public function addExternalLink(ExternalLink $externalLink): static
    {
        if (!$this->externalLinks->contains($externalLink)) {
            $this->externalLinks->add($externalLink);
            $externalLink->setAppli($this);
        }

        return $this;
    }

    public function removeExternalLink(ExternalLink $externalLink): static
    {
        if ($this->externalLinks->removeElement($externalLink)) {
            // set the owning side to null (unless already changed)
            if ($externalLink->getAppli() === $this) {
                $externalLink->setAppli(null);
            }
        }

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
            $role->setAppli($this);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        if ($this->roles->removeElement($role)) {
            // set the owning side to null (unless already changed)
            if ($role->getAppli() === $this) {
                $role->setAppli(null);
            }
        }

        return $this;
    }
}
