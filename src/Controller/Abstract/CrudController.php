<?php

declare(strict_types=1);

namespace App\Controller\Abstract;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

abstract class CrudController extends AbstractController
{
    abstract protected function getConfig(): array;

    protected ServiceEntityRepositoryInterface $repository;
    protected array $config;

    public function __construct(ServiceEntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->config = $this->getConfig();
        $this->validateConfig($this->config);
    }

    protected function validateConfig(array $config): void
    {
        $requiredKeys = ['route_prefix', 'entity_class', 'entity_key', 'form_class', 'main_title', 'index_cols', 'index_backlink'];
        foreach ($requiredKeys as $key) {
            !isset($config[$key]) ? throw new \InvalidArgumentException(sprintf('Configuration key "%s" is missing.', $key)) : null;
        }
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $objects = $this->repository->findAll();

        return $this->render('abstract/crud/index.html.twig', [
            'config' => $this->getConfig(),
            'objects' => $objects,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function new(?int $id, Request $request, EntityManagerInterface $em): Response
    {
        $isNewObject = empty($id);
        if ($isNewObject) {
            $object = new ($this->getConfig()['entity_class'])();
        } else {
            $object = $this->repository->find($id);
            empty($object) ? throw new NotFoundHttpException($this->getConfig()['entity_class'] . ' object not found.') : null;
        }

        $form = $this->createForm($this->getConfig()['form_class'], $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($object);
            $em->flush();

            $this->addFlash('success', ['message' => $isNewObject ? 'form.flash.added' : 'form.flash.updated']);

            return $this->redirectToRoute($this->getConfig()['route_prefix'] . 'index');
        }

        return $this->render($isNewObject ? 'abstract/crud/new.html.twig' : 'abstract/crud/edit.html.twig', [
            'config' => $this->getConfig(),
            'form' => $form,
        ]);
    }

    #[IsCsrfTokenValid(new Expression('"delete-" ~ args["id"]'), tokenKey: 'delete_token')]
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $object = $this->repository->find($id);
        empty($object) ? throw new NotFoundHttpException($this->getConfig()['entity_class'] . ' object not found.') : null;

        $em->remove($object);
        $em->flush();

        $this->addFlash('success', ['message' => 'form.flash.deleted']);

        return $this->redirectToRoute($this->getConfig()['route_prefix'] . 'index');
    }
}
