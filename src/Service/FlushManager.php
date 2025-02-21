<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FlushManager
{
    public function __construct(private EntityManagerInterface $entityManager, private LoggerInterface $logger, private RequestStack $requestStack)
    {
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
