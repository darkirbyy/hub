<?php

declare(strict_types=1);

namespace App\Extension;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FlushManager
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private FlashBagInterface $flashBag;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->flashBag = $requestStack->getSession()->getFlashBag();
    }

    public function persist(object $object, array $flashSuccess = [])
    {
        try {
            $this->entityManager->persist($object);
            $this->entityManager->flush();
            if (!empty($flashSuccess)) {
                $this->flashBag->add('success', $flashSuccess);
            }
        } catch (\Exception $e) {
            $this->logger->warning('Error while trying to persist the entity {entity}. Error: {error}', ['entity' => $object::class, 'error' => $e->getMessage()]);
            if (!empty($flashSuccess)) {
                $this->flashBag->add('danger', ['message' => 'form.flash.error']);
            }
        }
    }

    public function remove(object $object, array $flashSuccess = [])
    {
        try {
            $this->entityManager->remove($object);
            $this->entityManager->flush();
            if (!empty($flashSuccess)) {
                $this->flashBag->add('success', $flashSuccess);
            }
        } catch (\Exception $e) {
            $this->logger->warning('Error while trying to remove the entity {entity}. Error: {error}', ['entity' => $object::class, 'error' => $e->getMessage()]);
            if (!empty($flashSuccess)) {
                $this->flashBag->add('danger', ['message' => 'form.flash.error']);
            }
        }
    }
}
