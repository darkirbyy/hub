<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Hub\Right;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class ExtendedEntityUserProvider extends EntityUserProvider
{
    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly string $classOrAlias,
        private readonly ?string $property = null,
        private readonly ?string $managerName = null,
        private readonly ?string $appName = null,
    ) {
        parent::__construct($registry, $classOrAlias, $property, $managerName);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = parent::loadUserByIdentifier($identifier);

        $this->computeRoles($user);

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $refreshedUser = parent::refreshUser($user);

        $this->computeRoles($refreshedUser);

        return $refreshedUser;
    }

    public function computeRoles(UserInterface $user): void
    {
        $roles = array_map(fn (Right $r) => $r->getRole(), array_filter($user->getRights()->toArray(), fn (Right $r) => $r->getAppli()->getName() === $this->appName));
        if (empty($roles)) {
            throw new UserNotFoundException('User has no role in this application.');
        }
        $user->setRoles(array_values($roles));
    }
}
