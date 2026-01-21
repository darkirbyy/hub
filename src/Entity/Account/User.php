<?php

declare(strict_types=1);

namespace App\Entity\Account;

use App\Entity\Hub\Right;
use App\Repository\Account\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Entity\File as FileMeta;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

/**
 * The User is described by a username, that must be unique.
 * The password and image are editable by the user.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(fields: ['username'])]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['username'])]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    #[Assert\Regex('/^[a-zA-Z0-9\-_]+$/', 'user.error.invalidUsername')]
    private ?string $username = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::JSON)]
    private ?array $roles = null;

    #[ORM\Column]
    private ?string $avatarPath = null;

    /**
     * @var Collection<int, Right>
     */
    #[ORM\ManyToMany(targetEntity: Right::class, inversedBy: 'users')]
    private Collection $rights;

    #[
        Vich\UploadableField(
            mapping: 'users',
            fileNameProperty: 'imageMeta.name',
            size: 'imageMeta.size',
            mimeType: 'imageMeta.mimeType',
            originalName: 'imageMeta.originalName',
            dimensions: 'imageMeta.dimensions',
        ),
    ]
    #[Assert\Image(maxSize: '1Mi', minWidth: 64, maxWidth: 1024, minRatio: 0.25, maxRatio: 4, mimeTypes: ['image/jpeg', 'image/png', 'image/webp'], detectCorrupted: true)]
    private ?File $imageFile = null;

    #[ORM\Embedded(class: 'Vich\UploaderBundle\Entity\File')]
    private ?FileMeta $imageMeta = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $imageUpdatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAdd = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateUpdate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateLastCo = null;

    public function __construct()
    {
        $this->imageMeta = new FileMeta();
        $this->rights = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getUsername() ?? '';
    }

    #[ORM\PrePersist]
    public function onPrePersit(): void
    {
        $this->dateAdd = new \DateTime();
        $this->onPreUpdate();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->dateUpdate = new \DateTime();
    }

    public function __serialize(): array
    {
        $data = [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => hash('crc32c', $this->getPassword()),
            'roles' => $this->getRoles(),
        ];

        return $data;
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->roles = $data['roles'];
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    #[\Deprecated]
    public function eraseCredentials(): void {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles ?? [];
    }

    public function setRoles(?array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getAvatarPath(): ?string
    {
        return $this->avatarPath;
    }

    public function setAvatarPath(string $avatarPath): static
    {
        $this->avatarPath = $avatarPath;

        return $this;
    }

    /**
     * @return Collection<int, Right>
     */
    public function getRights(): Collection
    {
        return $this->rights;
    }

    public function addRight(Right $right): static
    {
        if (!$this->rights->contains($right)) {
            $this->rights->add($right);
        }

        return $this;
    }

    public function removeRight(Right $right): static
    {
        $this->rights->removeElement($right);

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

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    public function setDateAdd(?\DateTimeInterface $dateAdd): static
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(?\DateTimeInterface $dateUpdate): static
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    public function getDateLastCo(): ?\DateTimeInterface
    {
        return $this->dateLastCo;
    }

    public function setDateLastCo(?\DateTimeInterface $dateLastCo): static
    {
        $this->dateLastCo = $dateLastCo;

        return $this;
    }
}
