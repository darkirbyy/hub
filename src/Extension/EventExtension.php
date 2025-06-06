<?php

declare(strict_types=1);

namespace App\Extension;

use App\Service\FlushManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

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
        return [LoginSuccessEvent::class => 'onLoginSucess'];
    }

    /**
     * Handles the login success event by updating the user's last connection date and redirecting it.
     *
     * @param LoginSuccessEvent $event the login success event
     */
    public function onLoginSucess(LoginSuccessEvent $event): void
    {
        /** @var \App\Entity\Account\User $user */
        $user = $event->getUser();

        $user->setDateLastCo(new \DateTime());
        $this->fm->persist($user);

        // $request = $event->getRequest();
        $targetPath = $event->getRequest()->getSession()->get('_login_target_path');

        if ($targetPath) {
            $event->getRequest()->getSession()->remove('_login_target_path');
            $event->setResponse(new RedirectResponse($targetPath));
        }
    }
}
