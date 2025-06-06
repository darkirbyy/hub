<?php

declare(strict_types=1);

namespace App\Extension;

use App\Entity\Account\User;
use App\Entity\Hub\Right;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preUpdate, method: 'preUpdateUser', entity: User::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdateRight', entity: Right::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemoveRight', entity: Right::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemoveRight', entity: Right::class)]
class FormEventExtension
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->pendingUsers = [];
    }

    public function preUpdateUser(User $user, PreUpdateEventArgs $event): void
    {
        $this->updateUser($user);
    }

    public function postUpdateRight(Right $right, PostUpdateEventArgs $event): void
    {
        $this->updateUsers($right->getUsers()->toArray());
    }

    public function preRemoveRight(Right $right, PreRemoveEventArgs $event): void
    {
        $this->pendingUsers = $right->getUsers()->toArray();
    }

    public function postRemoveRight(Right $right, PostRemoveEventArgs $event): void
    {
        $this->updateUsers($this->pendingUsers);
    }

    private function updateUsers(array $users): void
    {
        foreach ($users as $user) {
            $this->updateUser($user);
            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();
    }

    private function updateUser(User $user)
    {
        $rights = $user->getRights()->toArray();
        $roles = array_map(fn (Right $r) => $r->fullRole(), $rights);
        $user->setRoles($roles);
    }
}
