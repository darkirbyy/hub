<?php

declare(strict_types=1);

namespace App\Extension;

use App\Service\FlushManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginExtension implements EventSubscriberInterface
{
    private $fm;

    public function __construct(FlushManager $fm)
    {
        $this->fm = $fm;
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

        $this->fm->persist($user);
    }
}
