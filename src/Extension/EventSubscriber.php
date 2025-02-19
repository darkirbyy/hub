<?php

declare(strict_types=1);

namespace App\Extension;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class EventSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        // return the subscribed events, their methods and priorities
        return [LoginSuccessEvent::class => 'onLoginSucess'];
    }

    public function onLoginSucess(LoginSuccessEvent $event): void
    {
        /** @var \App\Entity\Account\User $user */
        $user = $event->getUser();
        $user->setDateLastCo(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();
    }
}
