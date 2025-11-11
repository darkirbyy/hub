<?php

declare(strict_types=1);

namespace App\Extension;

use App\Service\FlushManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

/**
 * Extension that subscribes to different events and add some logic.
 */
class EventExtension implements EventSubscriberInterface
{
    public function __construct(private FlushManager $fm)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onLoginSucess', LogoutEvent::class => 'onLogout'];
    }

    /**
     * Handles the login success event by updating the user's last connection date,
     * then forcing the redirect to the request page in session, or default behavior otherwise.
     *
     * @param LoginSuccessEvent $event the login success event
     */
    public function onLoginSucess(LoginSuccessEvent $event): void
    {
        /** @var \App\Entity\Account\User $user */
        $user = $event->getUser();

        $user->setDateLastCo(new \DateTime());
        $this->fm->persist($user);

        $targetPath = $event->getRequest()->getSession()->get('hub/login-target-path');

        if ($targetPath) {
            $event->getRequest()->getSession()->remove('hub/login-target-path');
            $event->setResponse(new RedirectResponse($targetPath));
        }
    }

    /**
     * Handles the logout by forcing the redirection to the request page in session, or default behavior otherwise.
     *
     * @param LogoutEvent $event the logout event
     */
    public function onLogout(LogoutEvent $event): void
    {
        $targetPath = $event->getRequest()->getSession()->get('hub/logout-target-path');

        if ($targetPath) {
            $event->getRequest()->getSession()->remove('hub/logout-target-path');
            $event->setResponse(new RedirectResponse($targetPath));
        }
    }
}
