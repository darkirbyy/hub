<?php

declare(strict_types=1);

namespace App\Entity\Account;

use App\Repository\Account\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Entity\File as FileMeta;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
#[UniqueEntity(fields: ['username'])]
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

    #[ORM\Column]
    #[Assert\NotNull]
    private ?bool $metaAdmin = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?MetaRole $metaRole = null;

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
        $this->metaAdmin = false;
        $this->imageMeta = new FileMeta();
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
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            // 'roles' => $this->getRoles(),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->password = $data['password'];
        // $this->roles = $data['roles'];
    }

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

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        $roles[] = $this->isMetaAdmin() ? 'ROLE_ADMIN' : 'ROLE_USER';

        return array_unique($roles);
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

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isMetaAdmin(): ?bool
    {
        return $this->metaAdmin;
    }

    public function setMetaAdmin(?bool $metaAdmin): static
    {
        $this->metaAdmin = $metaAdmin;

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

    public function getMetaRole(): ?MetaRole
    {
        return $this->metaRole;
    }

    public function setMetaRole(?MetaRole $metaRole): static
    {
        $this->metaRole = $metaRole;

        return $this;
    }
}
