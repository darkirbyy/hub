<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Service to persist/remove any entity into/from the database, handling the flash messages and logging the errors.
 */
class FlushManager
{
    private FlashBagInterface $flashBag;

    public function __construct(private EntityManagerInterface $entityManager, private LoggerInterface $logger, private RequestStack $requestStack)
    {
        $this->flashBag = $requestStack->getSession()->getFlashBag();
    }

    /**
     * Persists an entity into the database, displaying a customizable success flash message upon success.
     * Catches any ORM exception to log it and display a general error flash message otherwise.
     *
     * @param object $object       the entity to persist
     * @param array  $flashSuccess an optional flash message to display if the transaction is successful
     */
    public function persist(object $object, array $flashSuccess = []): void
    {
        try {
            $this->entityManager->persist($object);
            $this->entityManager->flush();
            if (!empty($flashSuccess)) {
                $this->flashBag->add('success', $flashSuccess);
            }
        } catch (ConstraintViolationException $e) {
            $this->logger->warning('Error while trying to persist the entity {entity}. Error: {error}', ['entity' => $object::class, 'error' => $e->getMessage()]);
            if (!empty($flashSuccess)) {
                $this->flashBag->add('danger', ['message' => 'form.flash.error']);
            }
        }
    }

    /**
     * Removes an entity from the database, displaying a customizable success flash message upon success.
     * Catches any ORM exception to log it and display a general error flash message otherwise.
     *
     * @param object $object       the entity to remove
     * @param array  $flashSuccess an optional flash message to display if the transaction is successful
     */
    public function remove(object $object, array $flashSuccess = []): void
    {
        try {
            $this->entityManager->remove($object);
            $this->entityManager->flush();
            if (!empty($flashSuccess)) {
                $this->flashBag->add('success', $flashSuccess);
            }
        } catch (ConstraintViolationException $e) {
            $this->logger->warning('Error while trying to remove the entity {entity}. Error: {error}', ['entity' => $object::class, 'error' => $e->getMessage()]);
            if (!empty($flashSuccess)) {
                $this->flashBag->add('danger', ['message' => 'form.flash.error']);
            }
        }
    }
}
