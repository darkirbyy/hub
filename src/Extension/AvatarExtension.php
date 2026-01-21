<?php

declare(strict_types=1);

namespace App\Extension;

use App\Entity\Account\User;
use App\Service\ImageResolver;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

/**
 * Update user avatar path every time the avatar is changed or when a user is added.
 */
#[AsEntityListener(event: Events::prePersist, method: 'prePersistUser', entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdateUser', entity: User::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdateUser', entity: User::class)]
class AvatarExtension
{
    public bool $avatarChanged;

    public function __construct(private EntityManagerInterface $entityManager, private ImageResolver $imageResolver) {}

    public function prePersistUser(User $user, PrePersistEventArgs $event): void
    {
        $this->updateAvatarPath($user);
    }

    public function preUpdateUser(User $user, PreUpdateEventArgs $event): void
    {
        $this->avatarChanged = $event->hasChangedField('imageUpdatedAt') || $event->hasChangedField('imageMeta.name');
    }

    public function postUpdateUser(User $user, PostUpdateEventArgs $event): void
    {
        if ($this->avatarChanged) {
            $this->updateAvatarPath($user);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    public function updateAvatarPath(User $user): void
    {
        $avatarPath = $this->imageResolver->getImagePath($user, 'default-avatar.png');
        $user->setAvatarPath($avatarPath);
    }
}
